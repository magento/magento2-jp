<?php
namespace MagentoJapan\Price\Model\Directory\Plugin;

use Magento\Directory\Model\PriceCurrency;
use MagentoJapan\Price\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;

class PriceRound
{
    /**
     * ScopeConfig
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Helper
     *
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
     * @param $amount
     * @param null $scope
     * @param null $currency
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
     * @param PriceCurrency $subject
     * @param \Closure $proceed
     * @param $amount
     * @param int $precision
     * @return mixed
     */
    public function aroundRoundPrice(PriceCurrency  $subject,
        \Closure $proceed,
        $amount,
        $precision = 2
    ) {
        $currency = $subject->getCurrency()->getCode();
            if (in_array($currency, $this->helper->getIntegerCurrencies())) {
            /**
             * Rounding method
             *
             * @var string $method rounding method
             */
            $method = $this->helper->getRoundMethod();
            if ($method != 'round') {
                return $method($amount);
            }
        }
        return $proceed($amount, $precision);
    }
}
