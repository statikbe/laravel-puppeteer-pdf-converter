<?php

namespace Statikbe\PuppeteerPdfConverter\Options;

use Statikbe\PuppeteerPdfConverter\Exceptions\InvalidMarginUnitException;

class PdfPageMargin
{
    public const MARGIN_IN_CM = 'cm';

    public const MARGIN_IN_PIXELS = 'px';

    /**
     * @var int
     */
    public $margin;

    /**
     * @var string
     */
    public $unit;

    public function __construct(int $margin, string $unit)
    {
        if ($unit !== self::MARGIN_IN_CM && $unit !== self::MARGIN_IN_PIXELS) {
            throw new InvalidMarginUnitException("The margin unit is not accepted: $unit");
        }

        $this->margin = $margin;
        $this->unit = $unit;
    }

    public function getApiValue(): string
    {
        return $this->margin.$this->unit;
    }
}
