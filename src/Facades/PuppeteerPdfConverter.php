<?php

namespace Statikbe\PuppeteerPdfConverter\Facades;

use Illuminate\Support\Facades\Facade;
use Statikbe\PuppeteerPdfConverter\Options\PdfOptions;

/**
 * @method static string convertRoute(string $routeName, array $routeParams, PdfOptions|null $pdfOptions=null, bool $createSignedUrl=true)
 * @method static string convertUrl(string $url, PdfOptions|null $pdfOptions=null)
 * @method static bool isLocalTunnelConfigured()
 *
 * @see \Statikbe\PuppeteerPdfConverter\PuppeteerPdfConverter
 */
class PuppeteerPdfConverter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Statikbe\PuppeteerPdfConverter\PuppeteerPdfConverter::class;
    }
}
