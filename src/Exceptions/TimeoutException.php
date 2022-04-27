<?php

    namespace Statikbe\PuppeteerPdfConverter\Exceptions;

    /**
     * If the PDF conversion API times out, this exception is thrown.
     * Class TimeoutException
     * @package Statikbe\PuppeteerPdfConverter\Exceptions
     */
    class TimeoutException extends PdfApiException
    {
        public const API_ERROR_TYPE = 'TIMEOUT_ERROR';
    }
