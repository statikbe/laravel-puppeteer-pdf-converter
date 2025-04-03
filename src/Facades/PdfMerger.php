<?php

namespace Statikbe\PuppeteerPdfConverter\Facades;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Facade;
use Statikbe\PuppeteerPdfConverter\Enum\MergerOutput;

/**
 * @method static string|Response mergePdfUrls(array $urls, MergerOutput $outputFormat = MergerOutput::URL, string $outputFileName = null)
 * @method static string|Response mergePdfFiles(array $filePaths, MergerOutput $outputFormat = MergerOutput::URL, string $outputFileName = null)
 *
 * @see \Statikbe\PuppeteerPdfConverter\PdfMerger
 */
class PdfMerger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Statikbe\PuppeteerPdfConverter\PdfMerger::class;
    }
}
