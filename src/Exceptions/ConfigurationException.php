<?php

    namespace Statikbe\PuppeteerPdfConverter\Exceptions;

    class ConfigurationException extends \Exception
    {
        public string $field;

        public static function create(string $field): self
        {
            $exception = new ConfigurationException("The Laravel Puppeteer PDF Converter library is not properly configured. Check if $field is properly configured.");
            $exception->field = $field;

            return $exception;
        }
    }
