<?php
namespace MagentoJapan\Price\Model\Directory\Plugin;

use Magento\Directory\Model\PriceCurrency;
use \Magento\Framework\View\Element\Context;

class Format
{

    /**
     * Scope Config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \MagentoJapan\Price\Helper\Data
     */
    private $helper;


    /**
     * Format constructor.
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