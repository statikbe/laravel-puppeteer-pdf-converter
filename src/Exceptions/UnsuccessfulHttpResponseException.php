<?php

namespace Statikbe\PuppeteerPdfConverter\Exceptions;

/**
 * If the web page that needs to be converted to PDF returns an HTTP status code that is not successful (i.e. 2xx),
 * this exception is thrown.
 *
 * Class UnsuccessfulHttpResponseException
 * @package Statikbe\PuppeteerPdfConverter\Exceptions
 */


class UnsuccessfulHttpResponseException extends PdfApiException
{
    public const API_ERROR_TYPE = 'UNSUCCESSFUL_HTTP_RESPONSE';

    protected $websiteStatusCode = null;

    public function setApiError(array $apiErrorFields): parent
    {
        parent::setApiError($apiErrorFields);
        if (array_key_exists('websiteStatusCode', $apiErrorFields)) {
            $this->websiteStatusCode = $apiErrorFields['websiteStatusCode'];
        }

        return $this;
    }

    /**
     * Returns the HTTP status code that was returned by the webpage url that was requested to be converted.
     * @return int
     */
    public function getWebsiteStatusCode(): int
    {
        return $this->websiteStatusCode;
    }
}
