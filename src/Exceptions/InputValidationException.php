<?php

namespace Statikbe\PuppeteerPdfConverter\Exceptions;

/**
 * Thrown when the input values or its structure to the AWS Lambda is not valid.
 */
class InputValidationException extends PdfApiException
{
    public const API_ERROR_TYPE = 'INPUT_VALIDATION_ERROR';
}
