<?php
declare(strict_types=1);


namespace MagentoJapan\Price\Model\Directory\Plugin;

use Magento\Directory\Model\PriceCurrency;
use MagentoJapan\Price\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Modify rounding method for converting currency.
 */
class PriceRound
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \MagentoJapan\Price\Helper\Data
     */
    private $helper;

    /**
     * @param Data $helper
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Data $helper,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
    }

    /**
     * Modify rounding method for converting currency.
     *
     * @param PriceCurrency $subject
     * @param \Closure $proceed
     * @param int $amount
     * @param string $scope
     * @param string $currency
     * @param int $precision
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
        if (in_array($subject->getCurrency()->getCode(), $this->helper->getIntegerCurrencies())) {
            /**
             * Rounding method
             *
             * @var string $method rounding method
             */
            $method = $this->helper->getRoundMethod($scope);

            if ($method != 'round') {
                return $method($amount);
            }
        }
        return $proceed($amount, $scope, $currency, $precision);
    }

    /**
     * Modify rounding method for converting currency.
     *
     * @param PriceCurrency $subject
     * @param \Closure $proceed
     * @param int $amount
     * @param int $precision
     * @return mixed
     */
    public function aroundRoundPrice(
        PriceCurrency $subject,
        \Closure $proceed,
        $amount,
        $precision = 2
    ) {
        $currency = $subject->getCurrency()->getCode();
        if (in_array($currency, $this->helper->getIntegerCurrencies())) {
            /** @var string $method */
            $method = $this->helper->getRoundMethod();
            if ($method != 'round') {
                return $method($amount);
            }
        }
        return $proceed($amount, $precision);
    }
}
