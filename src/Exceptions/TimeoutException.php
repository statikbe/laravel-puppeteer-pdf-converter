<?php

namespace Statikbe\PuppeteerPdfConverter\Exceptions;

/**
 * If the Puppeteer library times out, this exception is thrown.
 * Class TimeoutException
 */
class TimeoutException extends PdfApiException
{
    public const API_ERROR_TYPE = 'TIMEOUT_ERROR';
}
