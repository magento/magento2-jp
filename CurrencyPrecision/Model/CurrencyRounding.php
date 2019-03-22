<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Model;

use CommunityEngineering\CurrencyPrecision\Model\Config\CurrencyRoundingConfig;
use CommunityEngineering\CurrencyPrecision\Model\Config\Source\RoundingMode;

/**
 * Currency rounding service.
 */
class CurrencyRounding
{
    /**
     * @var CurrencyRoundingConfig
     */
    private $currencyRoundingConfig;

    /**
     * @param CurrencyRoundingConfig $currencyRoundingConfig
     */
    public function __construct(CurrencyRoundingConfig $currencyRoundingConfig)
    {
        $this->currencyRoundingConfig = $currencyRoundingConfig;
    }

    /**
     * Determine what precision has main currency unit.
     *
     * Main currency precision is defined by subunits. E.g. fo US dollar precision is 2 as 1 USD equals to 100 cents
     * so USD has 2 significant fraction digits. Japanese Yen (JPY) has precision 0 as no subunits currently in use.
     *
     * @param string $currencyCode
     * @return int
     */
    public function getPrecision(string $currencyCode): int
    {
        $formatter = $this->createCurrencyFormatter($currencyCode);
        $precision = $formatter->getAttribute(\NumberFormatter::MAX_FRACTION_DIGITS);
        return $precision;
    }

    /**
     * Round currency to significant precision.
     *
     * Rounding method may be configured at admin page at
     *
     * @param string $currencyCode
     * @param float $amount
     * @return float
     */
    public function round(string $currencyCode, float $amount): float
    {
        $roundingMode = $this->getRoundingMode();

        $formatter = $this->createCurrencyFormatter($currencyCode);
        $formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, $roundingMode);

        $formatted = $formatter->format($amount);
        $rounded = $formatter->parse($formatted, \NumberFormatter::TYPE_DOUBLE);
        return $rounded;
    }

    /**
     * Create Intl Number Formatter for currency.
     *
     * @param string $currencyCode
     * @return \NumberFormatter
     */
    private function createCurrencyFormatter(string $currencyCode): \NumberFormatter
    {
        return new \NumberFormatter('@currency=' . $currencyCode, \NumberFormatter::CURRENCY);
    }

    /**
     * Get Intl rounding mode.
     *
     * Read configured rounding mode and map to Intl constant value.
     *
     * @return int
     */
    private function getRoundingMode(): int
    {
        $roundingModesMap = [
            RoundingMode::UP => \NumberFormatter::ROUND_UP,
            RoundingMode::CEILING => \NumberFormatter::ROUND_CEILING,
            RoundingMode::DOWN => \NumberFormatter::ROUND_DOWN,
            RoundingMode::FLOOR => \NumberFormatter::ROUND_FLOOR,
            RoundingMode::HALFUP => \NumberFormatter::ROUND_HALFUP,
            RoundingMode::HALFEVEN => \NumberFormatter::ROUND_HALFEVEN,
            RoundingMode::HALFDOWN => \NumberFormatter::ROUND_HALFDOWN,
        ];
        $configuredMode =  $this->currencyRoundingConfig->getRoundingMode();

        if (!isset($roundingModesMap[$configuredMode])) {
            throw new \LogicException(
                sprintf('Configured rounding mode "%s" is not implemented.', $configuredMode)
            );
        }

        return $roundingModesMap[$configuredMode];
    }
}
