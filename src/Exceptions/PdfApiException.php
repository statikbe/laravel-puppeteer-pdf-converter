<?php

namespace Statikbe\PuppeteerPdfConverter\Exceptions;

/**
 * Base and default exception class for errors from the AWS lambda functions.
 *
 * @package Statikbe\PuppeteerPdfConverter\Exceptions
 */
class PdfApiException extends \Exception
{
    public static function create(array $apiErrorFields): self
    {
        return (new self())->setApiError($apiErrorFields);
    }

    public function setApiError(array $apiErrorFields): self
    {
        if (array_key_exists('message', $apiErrorFields)) {
            $this->message = $apiErrorFields['message'];
        }

        if (array_key_exists('title', $apiErrorFields)) {
            $this->message = $apiErrorFields['title'];
        }

        return $this;
    }
}
