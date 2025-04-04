<?php

namespace Statikbe\PuppeteerPdfConverter\Exceptions;

/**
 * Thrown when the input PDFs cannot be retrieved.
 */
class PdfFetchException extends PdfApiException
{
    public const API_ERROR_TYPE = 'PDF_FETCH_ERROR';
}
