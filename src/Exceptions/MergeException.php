<?php

namespace Statikbe\PuppeteerPdfConverter\Exceptions;

/**
 * Thrown when the PDF merge library used by the AWS Lambda returns an error.
 */
class MergeException extends PdfApiException
{
    public const API_ERROR_TYPE = 'PDF_MERGE_ERROR';
}
