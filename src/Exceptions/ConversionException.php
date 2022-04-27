<?php


    namespace Statikbe\PuppeteerPdfConverter\Exceptions;


    /**
     * If an error occurs during the PDF conversion, this error is thrown.
     * Class ConversionException
     * @package Statikbe\PuppeteerPdfConverter\Exceptions
     */
    class ConversionException extends PdfApiException {
        const API_ERROR_TYPE = 'CONVERSION_ERROR';

    }
