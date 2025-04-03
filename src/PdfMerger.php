<?php

namespace Statikbe\PuppeteerPdfConverter;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\StreamWrapper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Statikbe\PuppeteerPdfConverter\Enum\MergerOutput;
use Statikbe\PuppeteerPdfConverter\Exceptions\PdfApiException;

/**
 * A wrapper around an AWS Lambda to merge multiple PDFs into one PDF.
 *
 * Can output a url to the resulting PDF or a binary stream to the PDF.
 */
class PdfMerger
{
    /**
     * Merge a list of urls into 1 PDF file.
     * @param string[] $urls
     */
    public function mergePdfUrls(array $urls, MergerOutput $outputFormat = MergerOutput::URL, string $outputFileName = null): string|Response
    {
        $body = [
            'fileUris' => $urls,
        ];
        $this->addOutputToBody($body, $outputFormat, $outputFileName);

        return $this->sendMergeRequest($body, $outputFormat);
    }

    /**
     * Merge a list of binary base64 strings into 1 PDF.
     * @param string[] $filePaths
     */
    public function mergePdfFiles(array $filePaths, MergerOutput $outputFormat = MergerOutput::URL, string $outputFileName = null): string|Response
    {
        $body = [
            'fileBinaries' => collect($filePaths)
                ->map(fn ($filePath) => base64_encode(file_get_contents($filePath)))
                ->toArray(),
        ];
        $this->addOutputToBody($body, $outputFormat, $outputFileName);

        return $this->sendMergeRequest($body, $outputFormat);
    }

    private function addOutputToBody(array &$body, MergerOutput $outputFormat, ?string $outputFileName)
    {
        $body['output'] = [
            'variant' => $outputFormat->value,
        ];

        if ($outputFileName && $outputFormat === MergerOutput::URL) {
            $body['output']['fileName'] = $outputFileName;
        }
    }

    private function sendMergeRequest(array $body, MergerOutput $outputFormat): string|Response
    {
        $url = Config::string('puppeteer-pdf-converter.pdf_merger_api');
        $apiKey = Config::string('puppeteer-pdf-converter.pdf_merger_api_key');

        try {
            $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-key' => $apiKey,
                ])
                ->post($url, $body);

            switch ($outputFormat) {
                case MergerOutput::URL:
                    return json_decode($response->getBody(), true)['url'];
                case MergerOutput::BASE64:
                default:
                    $resource = fopen('php://temp', 'r+');
                    fwrite($resource, base64_decode((string)$response->getBody()));
                    rewind($resource);

                    return new Response(StreamWrapper::getResource($resource), 200, ['Content-Type' => 'application/pdf']);
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 600) {
                    $error = json_decode($response->getBody(), true);
                    // Log error details for debugging
                    Log::error($error);

                    // Throw exception with error details
                    throw new PdfApiException('Error from AWS Lambda: ' . $error['title']);
                }
            }

            // Rethrow the original exception if the response doesn't have the error details we need
            throw $e;
        }
    }
}
