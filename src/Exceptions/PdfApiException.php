<?php

    namespace Statikbe\PuppeteerPdfConverter\Exceptions;

    class PdfApiException extends \Exception
    {
        public function setApiError(array $apiErrorFields): self
        {
            if (array_key_exists('message', $apiErrorFields)) {
                $this->message = $apiErrorFields['message'];
            }

            return $this;
        }
    }
