<?php

namespace Statikbe\PuppeteerPdfConverter\Tests;

use Illuminate\Support\Facades\Http;
use Statikbe\PuppeteerPdfConverter\Enum\MergerOutput;
use Statikbe\PuppeteerPdfConverter\PdfMerger;

class PdfMergerTest extends TestCase
{
    public function testMergePdfUrls()
    {
        self::assertNotNull(config('puppeteer-pdf-converter.pdf_merger_api'));

        // Mocking successful HTTP request
        Http::fake([
            '*' => Http::response(['url' => 'http://fake-url.com/merged.pdf'], 200),
        ]);

        $pdfMerger = new PdfMerger();

        $urls = [
            'http://fake-url.com/pdf1.pdf',
            'http://fake-url.com/pdf2.pdf',
        ];

        $result = $pdfMerger->mergePdfUrls($urls, MergerOutput::URL);

        $this->assertEquals('http://fake-url.com/merged.pdf', $result);
    }

    // Here you can add more tests for the other methods.
    // Also, make sure to test error cases as well.
}
