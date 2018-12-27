<?php

namespace MagentoJapan\Price\Model\Directory\Plugin;

use Magento\Directory\Model\PriceCurrency;
use Magento\Framework\View\Element\Context;

/**
 * Modify currency format.
 */
class Format
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \MagentoJapan\Price\Helper\Data
     */
    private $helper;

    /**
     * @param Context $context
     * @param \MagentoJapan\Price\Helper\Data $helper
     */
    public function __construct(
        Context $context,
        \MagentoJapan\Price\Helper\Data $helper
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->helper = $helper;
    }

    /**
     * Modify currency format.
     *
     * @param PriceCurrency $subject
     * @param \Closure $proceed
     * @param float $amount
     * @param bool $includeContainer
     * @param int $precision
     * @param string $scope
     * @param string $currency
     * @return mixed|string
     */
    public function aroundFormat(
        PriceCurrency  $subject,
        \Closure $proceed,
        $amount,
        $includeContainer = true,
        $precision = \Magento\Directory\Model\PriceCurrency::DEFAULT_PRECISION,
        $scope = null,
        $currency = null
    ) {
        if (in_array($subject->getCurrency()->getCode(), $this->helper->getIntegerCurrencies())) {
            $precision = '0';

            $method = $this->helper->getRoundMethod($scope);
            $amount = $method($amount);

            return $subject->getCurrency($scope, $currency)
                ->formatPrecision($amount, $precision, [], $includeContainer);
        }
        return $proceed($amount, $includeContainer, $precision, $scope, $currency);
    }
}
