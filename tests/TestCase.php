<?php

namespace Statikbe\PuppeteerPdfConverter\Tests;

use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as Orchestra;
use Statikbe\PuppeteerPdfConverter\PuppeteerPdfConverterServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            PuppeteerPdfConverterServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        Config::set('puppeteer-pdf-converter.pdf_merger_api', 'https://example.com');
        Config::set('puppeteer-pdf-converter.pdf_merger_api_key', 'abc');
    }
}
