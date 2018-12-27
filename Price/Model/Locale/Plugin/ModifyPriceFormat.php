<?php

namespace MagentoJapan\Price\Model\Locale\Plugin;

use Magento\Framework\Locale\Format;
use Magento\Framework\App\ScopeResolverInterface;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Directory\Model\CurrencyFactory;
use MagentoJapan\Price\Helper\Data;

/**
 * Modify JPY price formatting.
 */
class ModifyPriceFormat
{
    /**
     * @var ScopeResolverInterface
     */
    private $_scopeResolver;

    /**
     * @var ResolverInterface
     */
    private $_localeResolver;

    /**
     * @var CurrencyFactory
     */
    private $_currencyFactory;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @param ScopeResolverInterface $scopeResolver
     * @param ResolverInterface $localeResolver
     * @param CurrencyFactory $currencyFactory
     * @param Data $helper
     */
    public function __construct(
        ScopeResolverInterface $scopeResolver,
        ResolverInterface $localeResolver,
        CurrencyFactory $currencyFactory,
        Data $helper
    ) {
        $this->_scopeResolver = $scopeResolver;
        $this->_localeResolver = $localeResolver;
        $this->_currencyFactory = $currencyFactory;
        $this->helper = $helper;
    }

    /**
     * Modify precision for JPY.
     *
     * @param Format $subject
     * @param \Closure $proceed
     * @param string $localeCode
     * @param string $currencyCode
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetPriceFormat(
        Format $subject,
        \Closure $proceed,
        $localeCode = null,
        $currencyCode = null
    ) {
        if ($currencyCode) {
            $currency = $this->_currencyFactory->create()->load($currencyCode);
        } else {
            $currency = $this->_scopeResolver->getScope()->getCurrentCurrency();
        }

        $result = $proceed($localeCode, $currencyCode);

        if (in_array($currency->getCode(), $this->helper->getIntegerCurrencies())) {
            $result['precision'] = '0';
            $result['requiredPrecision'] = '0';
        }
        return $result;
    }

    /**
     * Remove comma from price on JPY.
     *
     * @param \Magento\Framework\Locale\Format $subject
     * @param string $value
     * @return array
     */
    public function beforeGetNumber(
        Format $subject,
        $value
    ) {
        $currency = $this->_scopeResolver->getScope()->getCurrentCurrency();
        $locale   = $this->_localeResolver->getLocale();
        $format = $subject->getPriceFormat($locale, $currency->getCode());

        if ($format['groupSymbol'] == '.') {
            $value = preg_replace('/\./', '', $value);
            $value = preg_replace('/,/', '.', $value);
        } else {
            $value = preg_replace('/,/', '', $value);
        }

        return [$value];
    }
}
