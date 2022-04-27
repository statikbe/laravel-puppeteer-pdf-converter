<?php

namespace Statikbe\PuppeteerPdfConverter;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Statikbe\PuppeteerPdfConverter\Exceptions\ConversionException;
use Statikbe\PuppeteerPdfConverter\Exceptions\PdfApiException;
use Statikbe\PuppeteerPdfConverter\Exceptions\TimeoutException;
use Statikbe\PuppeteerPdfConverter\Exceptions\UnsuccessfulHttpResponseException;
use Statikbe\PuppeteerPdfConverter\Options\PdfOptions;

class PuppeteerPdfConverter
{
    /**
     * Convert the webpage of the provided route name and route parameters to a PDF using the Puppeteer PDF API.
     * @param string $routeName
     * @param array $routeParams
     * @param PdfOptions|null $pdfOptions
     * @param bool $createSignedUrl Default is true. If you want to use this feature properly, you should check if the signature is valid in the route controller.
     * @return string
     * @throws TimeoutException
     * @throws ConversionException
     * @throws UnsuccessfulHttpResponseException
     */
    public function convertRoute(string $routeName, array $routeParams = [], PdfOptions $pdfOptions = null, bool $createSignedUrl = true): string
    {
        //get url to html pdf view to send to pdf conversion API:
        if ($createSignedUrl) {
            $websiteUrl = URL::temporarySignedRoute(
                $routeName,
                now()->addMinutes(config('puppeteer-pdf-converter.temporary_signed_route_ttl')),
                $routeParams
            );
        } else {
            $websiteUrl = URL::route(
                $routeName,
                $routeParams
            );
        }

        if (config('app.env') == 'local' && config('puppeteer-pdf-converter.ngrok_app_url')) {
            $path = parse_url($websiteUrl, PHP_URL_PATH) . '?' . parse_url($websiteUrl, PHP_URL_QUERY);
            $websiteUrl = config('puppeteer-pdf-converter.ngrok_app_url') . $path;
        }

        return $this->convertUrl($websiteUrl);
    }

    /**
     * Convert the webpage of the provided URL to a PDF using the Puppeteer PDF API.
     * @param string $url
     * @param PdfOptions|null $pdfOptions
     * @return string
     * @throws TimeoutException
     * @throws ConversionException
     * @throws UnsuccessfulHttpResponseException
     */
    public function convertUrl(string $url, PdfOptions $pdfOptions = null): string
    {
        if (! $pdfOptions) {
            //load from config if no options are provided:
            $pdfOptions = new PdfOptions();
        }

        // prepare API call:
        $pdfConversionApiUrl = config('puppeteer-pdf-converter.pdf_conversion_api');
        $queryStringArgs = $pdfOptions->getApiPdfOptions();
        $queryStringArgs['url'] = $url;
        $pdfConversionApiUrl .= '?' . http_build_query($queryStringArgs);

        $response = Http::get($pdfConversionApiUrl);

        //if the request is successful return the url to the PDF.
        if ($response->successful()) {
            return $response->json('url');
        }
        //else an error occurred and we throw an exception
        throw $this->createApiException($response);
    }

    /**
     * Checks if an Ngrok tunnel url is configured. This helper check can be used to set up conditions to check if the
     * signed urls need to be validated. If we change the url with the Ngrok url, the signature will no longer be valid.
     * @return bool
     */
    public function isLocalTunnelConfigured(): bool
    {
        return (config('puppeteer-pdf-converter.ngrok_app_url', null) && config('app.env') === 'local');
    }

    /**
     * Create the correct exception based on the api error message body.
     * @param \Illuminate\Http\Client\Response $response
     * @return PdfApiException
     */
    private function createApiException(\Illuminate\Http\Client\Response $response): PdfApiException
    {
        $errorType = $response->json('type');
        if ($errorType === UnsuccessfulHttpResponseException::API_ERROR_TYPE) {
            return (new UnsuccessfulHttpResponseException())->setApiError($response->json());
        } elseif ($errorType === TimeoutException::API_ERROR_TYPE) {
            return (new TimeoutException())->setApiError($response->json());
        } else {
            //fallback to conversion error:
            return (new ConversionException())->setApiError($response->json());
        }
    }
}
