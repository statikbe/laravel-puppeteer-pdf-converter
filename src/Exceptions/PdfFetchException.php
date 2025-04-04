<?php

namespace Statikbe\PuppeteerPdfConverter\Exceptions;

/**
 * Thrown when the input PDFs cannot be retrieved.
 *
 * @package Statikbe\PuppeteerPdfConverter\Exceptions
 */
class PdfFetchException extends PdfApiException
{
    public const API_ERROR_TYPE = 'PDF_FETCH_ERROR';
}
