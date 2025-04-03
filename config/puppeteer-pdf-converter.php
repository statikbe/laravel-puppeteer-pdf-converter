<?php
// config for statikbe/laravel-puppeteer-pdf-converter
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

    /*
     * The URL to the AWS Lambda API to merge multiple PDFs to one PDF.
     */
    'pdf_merger_api' => env('PDF_MERGER_API'),

    /*
     * The API key for the AWS Lambda API to merge multiple PDFs to one PDF.
     */
    'pdf_merger_api_key' => env('PDF_MERGER_API_KEY'),
];
