<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Plugin\Locale;

use CommunityEngineering\CurrencyPrecision\Model\CurrencyRounding;
use Magento\Framework\Locale\Format;

/**
 * Plugin for implement correct locale aware number parsing.
 */
class LocalizedFormat
{
    /**
     * @var \CommunityEngineering\CurrencyPrecision\Model\CurrencyRounding
     */
    protected $currencyRounding;

    /**
     * LocalizedFormat constructor.
     *
     * @param \CommunityEngineering\CurrencyPrecision\Model\CurrencyRounding $currencyRounding
     */
    public function __construct(
        CurrencyRounding $currencyRounding
    ) {
        $this->currencyRounding = $currencyRounding;
    }

    /**
     * Parse number with locale-aware parser.
     *
     * @param Format $format
     * @param mixed $value
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @see \Magento\Framework\Locale\Format::getNumber()
     */
    public function beforeGetNumber(Format $format, $value)
    {
        if (!is_string($value)) {
            return [$value];
        }

        $formatter = $this->currencyRounding->getNumberFormatter(null, [], null, \NumberFormatter::DECIMAL);

        $number = $formatter->parse($value);

        return [(string)$number]; // trigger core logic with dot handling for backward compatibility
    }
}
