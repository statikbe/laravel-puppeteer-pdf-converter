<?php

    namespace Statikbe\PuppeteerPdfConverter\Options;

    class PdfOptions
    {
        /**
         * @var int
         */
        private $width;

        /**
         * @var int
         */
        private $height;

        /**
         * @var float
         */
        private $scale;

        /**
         * @var PdfPageMargin
         */
        private $pageMarginTop;

        /**
         * @var PdfPageMargin
         */
        private $pageMarginBottom;

        /**
         * @var PdfPageMargin
         */
        private $pageMarginLeft;

        /**
         * @var PdfPageMargin
         */
        private $pageMarginRight;

        public function __construct()
        {
            //set default the configuration PDF options and allow override with setters.
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
         * @param string $configKey
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
         * @return array
         */
        public function getApiPdfOptions(): array
        {
            $options = [];

            $options['width'] = $this->width;
            $options['height'] = $this->height;
            $options['scale'] = $this->scale;
            $options['paper_margin_top'] = $this->pageMarginTop ? $this->pageMarginTop->getApiValue() : null;
            $options['paper_margin_bottom'] = $this->pageMarginBottom ? $this->pageMarginBottom->getApiValue() : null;
            $options['paper_margin_left'] = $this->pageMarginLeft ? $this->pageMarginLeft->getApiValue() : null;
            $options['paper_margin_right'] = $this->pageMarginRight ? $this->pageMarginRight->getApiValue() : null;

            return $options;
        }

        //GETTERS & SETTERS:

        /**
         * @return int
         */
        public function getWidth(): int
        {
            return $this->width;
        }

        /**
         * @param int $width
         * @return PdfOptions
         */
        public function setWidth(int $width): self
        {
            $this->width = $width;

            return $this;
        }

        /**
         * @return int
         */
        public function getHeight(): int
        {
            return $this->height;
        }

        /**
         * @param int $height
         * @return PdfOptions
         */
        public function setHeight(int $height): self
        {
            $this->height = $height;

            return $this;
        }

        /**
         * @return float
         */
        public function getScale(): float
        {
            return $this->scale;
        }

        /**
         * @param float $scale
         * @return PdfOptions
         */
        public function setScale(float $scale): self
        {
            $this->scale = $scale;

            return $this;
        }

        /**
         * Sets all page margins at once.
         * @param PdfPageMargin $pageMargin
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

        /**
         * @return PdfPageMargin
         */
        public function getPageMarginTop(): PdfPageMargin
        {
            return $this->pageMarginTop;
        }

        /**
         * @param PdfPageMargin $pageMarginTop
         * @return PdfOptions
         */
        public function setPageMarginTop(PdfPageMargin $pageMarginTop): self
        {
            $this->pageMarginTop = $pageMarginTop;

            return $this;
        }

        /**
         * @return PdfPageMargin
         */
        public function getPageMarginBottom(): PdfPageMargin
        {
            return $this->pageMarginBottom;
        }

        /**
         * @param PdfPageMargin $pageMarginBottom
         * @return PdfOptions
         */
        public function setPageMarginBottom(PdfPageMargin $pageMarginBottom): self
        {
            $this->pageMarginBottom = $pageMarginBottom;

            return $this;
        }

        /**
         * @return PdfPageMargin
         */
        public function getPageMarginLeft(): PdfPageMargin
        {
            return $this->pageMarginLeft;
        }

        /**
         * @param PdfPageMargin $pageMarginLeft
         * @return PdfOptions
         */
        public function setPageMarginLeft(PdfPageMargin $pageMarginLeft): self
        {
            $this->pageMarginLeft = $pageMarginLeft;

            return $this;
        }

        /**
         * @return PdfPageMargin
         */
        public function getPageMarginRight(): PdfPageMargin
        {
            return $this->pageMarginRight;
        }

        /**
         * @param PdfPageMargin $pageMarginRight
         * @return PdfOptions
         */
        public function setPageMarginRight(PdfPageMargin $pageMarginRight): self
        {
            $this->pageMarginRight = $pageMarginRight;

            return $this;
        }
    }
