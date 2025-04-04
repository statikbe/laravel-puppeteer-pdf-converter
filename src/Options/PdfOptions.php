<?php

namespace Statikbe\PuppeteerPdfConverter\Options;

class PdfOptions
{
    /**
     * @var int|null
     */
    private $width;

    /**
     * @var int|null
     */
    private $height;

    /**
     * @var float|null
     */
    private $scale;

    /**
     * @var PdfPageMargin|null
     */
    private $pageMarginTop;

    /**
     * @var PdfPageMargin|null
     */
    private $pageMarginBottom;

    /**
     * @var PdfPageMargin|null
     */
    private $pageMarginLeft;

    /**
     * @var PdfPageMargin|null
     */
    private $pageMarginRight;

    public function __construct()
    {
        // set default the configuration PDF options and allow override with setters.
        $this->setPdfOptionsFromConfig();
    }

    private function setPdfOptionsFromConfig(): void
    {
        $this->width = $this->getPdfOptionFromConfig('pdf_width');
        $this->height = $this->getPdfOptionFromConfig('pdf_height');
        $this->scale = $this->getPdfOptionFromConfig('pdf_scale');
        $this->pageMarginTop = $this->getPdfMarginFromConfig('paper_margin_top');
        $this->pageMarginBottom = $this->getPdfMarginFromConfig('paper_margin_bottom');
        $this->pageMarginLeft = $this->getPdfMarginFromConfig('paper_margin_left');
        $this->pageMarginRight = $this->getPdfMarginFromConfig('paper_margin_right');
    }

    /**
     * Adds a configuration if it exists, to the PDF options array for the API.
     *
     * @return int|string|null
     */
    private function getPdfOptionFromConfig(string $configKey)
    {
        $configField = "puppeteer-pdf-converter.$configKey";

        return config($configField, null);
    }

    private function getPdfMarginFromConfig(string $configKey): ?PdfPageMargin
    {
        $marginFromConfig = $this->getPdfOptionFromConfig($configKey);

        if ($marginFromConfig) {
            $margin = $this->parsePdfMarginFromConfigString($marginFromConfig, PdfPageMargin::MARGIN_IN_PIXELS);
            if (! $margin) {
                $margin = $this->parsePdfMarginFromConfigString($marginFromConfig, PdfPageMargin::MARGIN_IN_CM);
            }

            return $margin;
        }

        return null;
    }

    private function parsePdfMarginFromConfigString(string $marginFromConfig, string $marginUnit): ?PdfPageMargin
    {
        if ($marginFromConfig) {
            if (strpos($marginFromConfig, $marginUnit) !== false) {
                $margin = strstr($marginFromConfig, $marginUnit, true);

                return new PdfPageMargin($margin, $marginUnit);
            }
        }

        return null;
    }

    /**
     * Returns the PDF option in an array formatted as query strings for the API.
     */
    public function getApiPdfOptions(): array
    {
        $options = [];
        if ($this->width) {
            $options['width'] = $this->width;
        }
        if ($this->height) {
            $options['height'] = $this->height;
        }
        if ($this->scale) {
            $options['scale'] = $this->scale;
        }
        if ($this->pageMarginTop) {
            $options['paper_margin_top'] = $this->pageMarginTop->getApiValue();
        }
        if ($this->pageMarginBottom) {
            $options['paper_margin_bottom'] = $this->pageMarginBottom->getApiValue();
        }
        if ($this->pageMarginLeft) {
            $options['paper_margin_left'] = $this->pageMarginLeft->getApiValue();
        }
        if ($this->pageMarginRight) {
            $options['paper_margin_right'] = $this->pageMarginRight->getApiValue();
        }

        return $options;
    }

    // GETTERS & SETTERS:

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getScale(): float
    {
        return $this->scale;
    }

    public function setScale(float $scale): self
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * Sets all page margins at once.
     *
     * @return $this
     */
    public function setPageMargins(PdfPageMargin $pageMargin): self
    {
        $this->setPageMarginTop($pageMargin);
        $this->setPageMarginBottom($pageMargin);
        $this->setPageMarginLeft($pageMargin);
        $this->setPageMarginRight($pageMargin);

        return $this;
    }

    public function getPageMarginTop(): PdfPageMargin
    {
        return $this->pageMarginTop;
    }

    public function setPageMarginTop(PdfPageMargin $pageMarginTop): self
    {
        $this->pageMarginTop = $pageMarginTop;

        return $this;
    }

    public function getPageMarginBottom(): PdfPageMargin
    {
        return $this->pageMarginBottom;
    }

    public function setPageMarginBottom(PdfPageMargin $pageMarginBottom): self
    {
        $this->pageMarginBottom = $pageMarginBottom;

        return $this;
    }

    public function getPageMarginLeft(): PdfPageMargin
    {
        return $this->pageMarginLeft;
    }

    public function setPageMarginLeft(PdfPageMargin $pageMarginLeft): self
    {
        $this->pageMarginLeft = $pageMarginLeft;

        return $this;
    }

    public function getPageMarginRight(): PdfPageMargin
    {
        return $this->pageMarginRight;
    }

    public function setPageMarginRight(PdfPageMargin $pageMarginRight): self
    {
        $this->pageMarginRight = $pageMarginRight;

        return $this;
    }
}
