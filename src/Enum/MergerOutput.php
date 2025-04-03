<?php

namespace Statikbe\PuppeteerPdfConverter\Enum;

enum MergerOutput: string
{
    case URL = 'url';
    case BASE64 = 'base64';
}
