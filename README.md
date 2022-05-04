# Laravel Puppeteer PDF Converter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/statikbe/laravel-puppeteer-pdf-converter.svg?style=flat-square)](https://packagist.org/packages/statikbe/laravel-puppeteer-pdf-converter)
[![Total Downloads](https://img.shields.io/packagist/dt/statikbe/laravel-puppeteer-pdf-converter.svg?style=flat-square)](https://packagist.org/packages/statikbe/laravel-puppeteer-pdf-converter)


This is a convenience wrapper for a service that converts an HTML page to PDF. The service uses [Puppeteer.js](https://pptr.dev/) 
and is developed in-house at [Statik](https://www.statik.be). The code for the service is not yet open-sourced, but can be shared upon request.

## Installation

You can install the package via composer:

```bash
composer require statikbe/laravel-puppeteer-pdf-converter
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="puppeteer-pdf-converter-config"
```

This is the contents of the published config file:

```php
<?php

return [
    /*
     * The URL to the AWS Lambda API to convert HTML to PDF with Puppeteer.
     */
    'pdf_conversion_api' => env('PDF_CONVERSION_API'),

    /*
     * If the url is generated from a route name, a signed URL is created with a time-to-live (TTL). This is useful
     * when sensitive data is available on the URL, so it can only be accessed from a signed url.
     * Note: you need to check if the signature is valid in the controller of the route.
     */
    'temporary_signed_route_ttl' => 10,     // in minutes

    /*
     * If you develop with this library locally, you can setup NGROK or some other tunneling service to make your local
     * computer publicly avaialable for the PDF service, so you can test the PDF conversion while developing.
     */
    'ngrok_app_url' => env('NGROK_APP_URL'),

    /*
     * The paper width of the PDF (defaults to A4) (see Puppeteer docs for details, https://pptr.dev/#?product=Puppeteer&version=v10.4.0&show=api-pagepdfoptions)
     */
    'pdf_width' => null,

    /*
     * The paper height of the PDF (defaults to A4) (see Puppeteer docs for details, https://pptr.dev/#?product=Puppeteer&version=v10.4.0&show=api-pagepdfoptions)
     */
    'pdf_height' => null,

    /*
     * The scale of the web page rendering, allows to zoom in or out of the page (defaults to 1, must be between 0.1 and 2)
     */
    'pdf_scale' => 1,

    /*
     * The paper top margin (provide units in "px" or "cm", e.g. "40px")
     */
    'paper_margin_top' => null,

    /*
     * The paper bottom margin (provide units in "px" or "cm", e.g. "40px")
     */
    'paper_margin_bottom' => null,

    /*
     * The paper left margin (provide units in "px" or "cm", e.g. "40px")
     */
    'paper_margin_left' => null,

    /*
     * The paper right margin (provide units in "px" or "cm", e.g. "40px")
     */
    'paper_margin_right' => null,
];
```

## Configuration

**Required:** You need to set the url to the API in the `.env` with key `PDF_CONVERSION_API`.

**Optional:** The other configuration is optional. You can set default Puppeteer configuration, such as paper margins, scaling and paper width and height.
The paper width and height defaults to A4.
More information on possible Puppeteer configuration is available in [the Puppeteer docs](https://pptr.dev/#?product=Puppeteer&version=v10.4.0&show=api-pagepdfoptions).

You can also manually set the PDF configuration when you create the PDF, see below. However, it is important to note that the 
default configuration set in the config file, is always taken as the basis and is overridden by the manual options in code.
If a value is set to `null` in the config file or manual options, the option will not be send to the service 
(i.e. the option is disabled).   

## Usage

The library allows to convert an HTML page to a PDF for a route name (use `convertRoute()`) or for a URL (use `convertUrl()`).

If you want to override the PDF options that are set in the configuration file, you can create a `PdfOptions` object. 

```php
//set options and get pdf from conversion API
try {
    $pdfOptions = new PdfOptions();
    $pdfOptions->setScale(0.6)
        ->setPageMargins(new PdfPageMargin(40, PdfPageMargin::MARGIN_IN_PIXELS));
    $pdfUrl = PuppeteerPdfConverter::convertRoute('report_index', ['organisation' => $organisation], $pdfOptions);
    return redirect($pdfUrl);
}
catch(PdfApiException $exception){
    Log::error(sprintf('PDF report could not be created: %s (for organisation: %s)', $exception->getMessage(), $organisation));
    return view('pdf_error', ['error' => $exception->getMessage()]);
}
```

The following exceptions can occur:

- `TimeoutException`: 
    Since the PDF conversion service runs on AWS Lambda, it can take timeout due to cold-start issues. 
    If 20 seconds pass, this exception will be thrown. You can use this exception to implement a retry mechanism.
- `UnsuccessfulHttpResponseException`:
    When the given URL returns an HTTP status that is not successful (within the range of 200), this exception is thrown
    to avoid that error pages are rendered in the PDF.
- `ConversionException`: 
    If an internal error occurs on the service or in the Puppeteer library, this exception is returned.
- `PdfApiException`:
    This exception is the super class of all above exceptions, and can thus be used to catch all of the above exceptions 
    in one catch clause (as shown in the example). 
- `InvalidMarginUnitException`:
    This exception is thrown if the unit of the margins is something else than px or cm.   

### Temporary URLs

The library can generate temporary signed URLs. This is handy if you need to generate PDFs from routes that require authorisation. 
The PDF service cannot login to your application. But by using temporarily signed URLs, we can validate that the application 
has generated the URL and no one has tempered with it.

You need to check in the controller of the HTML page whether the signature of the URL is valid. 
See section *Local development* for an example.

## Local development

The PDF conversion service runs in the cloud. For local development, you can run it locally or use a tunneling service
to expose your local web server. Examples of such tunneling software is [Ngrok](https://ngrok.com/) or [Expose](https://expose.dev/). 
Or if you use [Valet](https://laravel.com/docs/9.x/valet), there is a [sharing command](https://laravel.com/docs/9.x/valet#sharing-sites)

```bash
valet share
```

If you use Ngrok, you can start the service (replace `APP_URL` with the value of the `APP_URL` key in your `.env` file):

```bash
ngrok http -host-header=APP_URL 80
```

You can then configure the tunneled URL in the `.env` file with key `NGROK_APP_URL`. You can also set URLs of Valet or Expose 
in this variable. For example:

```
NGROK_APP_URL=http://dba0-94-224-113-240.ngrok.io
```

In case you use temporary signed URLs, there is a convenience function to check if a local tunnel is configured 
(i.e. whether the `NGROK_APP_URL` is set and the environment is set to `local`). 

```php
PuppeteerPdfConverter::isLocalTunnelConfigured();
```

This can be used to bypass the valid signature check in your controller. Because the `NGROK_APP_URL` will be replaced 
in the generated URL, the signature will not be valid, hence we need to bypass the `$request->hasValidSignature()` check.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

You can use the Github issue tracker and pull requests are welcome!

## Credits

- [sten](https://github.com/sten)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
