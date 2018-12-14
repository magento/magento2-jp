<?php
namespace MagentoJapan\Price\Model\Directory\Plugin;

use \Magento\Directory\Model\PriceCurrency;
use \Magento\Framework\View\Element\Context;
use \MagentoJapan\Price\Model\Config\System;

class Format
{

    /**
     * Scope Config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var System
     */
    private $system;


    /**
     * Format constructor.
     * @param Context $context
     * @param System $system
     */
    public function __construct(
        Context $context,
        System $system
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->system = $system;
    }

    /**
     * Modify Currency Format
     *
     * @param PriceCurrency $subject          Price Currency Object
     * @param \Closure      $proceed          Closure
     * @param float         $amount           Price Amount
     * @param bool          $includeContainer Include Container Flag
     * @param int           $precision        Precision digits
     * @param null          $scope            Data scope
     * @param null          $currency         Currency Code
     *
     * @return mixed
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
        if (in_array($subject->getCurrency()->getCode(), $this->system->getIntegerCurrencies())) {
            $precision = '0';

            $method = $this->system->getRoundMethod($scope);
            $amount = $method($amount);

            return $subject->getCurrency($scope, $currency)
                ->formatPrecision($amount, $precision, [], $includeContainer);
        }
        return $proceed($amount, $includeContainer, $precision, $scope, $currency);
    }
}