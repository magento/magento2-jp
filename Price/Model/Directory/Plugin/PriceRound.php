<?php
namespace MagentoJapan\Price\Model\Directory\Plugin;

use \Magento\Directory\Model\PriceCurrency;
use \MagentoJapan\Price\Model\Config\System;
use \Magento\Framework\App\Config\ScopeConfigInterface;

class PriceRound
{

    /**
     * ScopeConfig
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * System configuration
     *
     * @var System
     */
    private $system;

    /**
     * ModifyPrice constructor.
     *
     * @param System $system
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        System $system,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->system = $system;
    }


    /**
     * Modify rounding method for converting currency
     *
     * @param PriceCurrency $subject   Price Currency
     * @param \Closure      $proceed   Closure
     * @param float         $amount    Price
     * @param null          $scope     Configuration Scope
     * @param null          $currency  Currency
     * @param int           $precision Currency Precision
     *
     * @return mixed
     */
    public function aroundConvertAndRound(
        PriceCurrency  $subject,
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
     * Modify rounding method
     *
     * @param PriceCurrency $subject   Price Currency
     * @param \Closure      $proceed   Closure
     * @param float         $amount    Price
     * @param int           $precision Currency precision
     *
     * @return mixed
     */
    public function aroundRound(PriceCurrency  $subject,
        \Closure $proceed,
        $amount,
        $precision = 2
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
        return $proceed($amount, $precision);
    }

}