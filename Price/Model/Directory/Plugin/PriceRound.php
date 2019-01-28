<?php
declare(strict_types=1);

namespace MagentoJapan\Price\Model\Directory\Plugin;

use \Magento\Directory\Model\PriceCurrency;
use \MagentoJapan\Price\Model\Config\System;

/**
 * Modify rounding method for converting currency.
 */
class PriceRound
{
    /**
     * System configuration
     *
     * @var System
     */
    private $system;

    /**
     * @param System $system
     */
    public function __construct(
        System $system
    ) {
        $this->system = $system;
    }

    /**
     * Modify rounding method for converting currency.
     *
     * @param PriceCurrency $subject Price Currency
     * @param \Closure $proceed Closure
     * @param float $amount Price
     * @param null $scope Configuration Scope
     * @param null $currency Currency
     * @param int $precision Currency Precision
     * @return mixed
     */
    public function aroundConvertAndRound(
        PriceCurrency $subject,
        \Closure $proceed,
        $amount,
        $scope = null,
        $currency = null,
        $precision = PriceCurrency::DEFAULT_PRECISION
    ) {
        if (in_array($subject->getCurrency()->getCode(), $this->system->getIntegerCurrencies())) {
            /**
             * Rounding method
             *
             * @var string $method rounding method
             */
            $method = $this->system->getRoundMethod($scope);

            if ($method != 'round') {
                return $method($amount);
            }
        }
        return $proceed($amount, $scope, $currency, $precision);
    }

    /**
     * Modify rounding method for converting currency.
     *
     * @param PriceCurrency $subject Price Currency
     * @param \Closure $proceed Closure
     * @param float $amount Price
     * @return mixed
     */
    public function aroundRound(
        PriceCurrency $subject,
        \Closure $proceed,
        $amount
    ) {
        $currency = $subject->getCurrency()->getCode();
        if (in_array($currency, $this->system->getIntegerCurrencies())) {
            /**
             * Rounding method
             *
             * @var string $method rounding method
             */
            $method = $this->system->getRoundMethod();
            if ($method != 'round') {
                return $method($amount);
            }
        }
        return $proceed($amount);
    }
}
