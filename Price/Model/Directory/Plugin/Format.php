<?php
declare(strict_types=1);

namespace MagentoJapan\Price\Model\Directory\Plugin;

use Magento\Directory\Model\PriceCurrency;
use Magento\Framework\View\Element\Context;
use MagentoJapan\Price\Helper\Data;

/**
 * Modify currency format.
 */
class Format
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @param Context $context
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        Data $helper
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
