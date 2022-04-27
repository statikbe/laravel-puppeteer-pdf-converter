<?php

namespace VendorName\Skeleton\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use VendorName\Skeleton\PuppeteerPdfConverterServiceProvider;

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
    }
}
