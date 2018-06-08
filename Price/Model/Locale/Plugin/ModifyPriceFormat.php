<?php
namespace MagentoJapan\Price\Model\Locale\Plugin;

use Magento\Framework\Locale\Format;
use \Magento\Framework\App\ScopeResolverInterface;
use \Magento\Framework\Locale\ResolverInterface;
use \Magento\Directory\Model\CurrencyFactory;
use MagentoJapan\Price\Helper\Data;

class ModifyPriceFormat
{
    /**
     * Scope Resolver
     *
     * @var \Magento\Framework\App\ScopeResolverInterface
     */
    private $_scopeResolver;

    /**
     * Locale Resolver
     *
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $_localeResolver;

    /**
     * Currency Factory
     *
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    private $_currencyFactory;

    /**
     * @var Data
     */
    private $helper;

    /**
     * Constructor
     *
     * @param ScopeResolverInterface $scopeResolver   Scope Resolver
     * @param ResolverInterface      $localeResolver  Locale Resolver
     * @param CurrencyFactory        $currencyFactory Currency Resolver
     * @param Data                                    $helper  Helper
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
     * Modify precision for JPY
     *
     * @param \Magento\Framework\Locale\Format $subject      Currency Format Obj
     * @param \Closure                         $proceed      Closure
     * @param null|string                      $localeCode   Locale Code
     * @param null|string                      $currencyCode Currency Code
     *
     * @return mixed
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

        if (in_array($currency->getCode() ,$this->helper->getIntegerCurrencies())) {
            $result['precision'] = '0';
            $result['requiredPrecision'] = '0';
        }
        return $result;
    }

    /**
     * Remove comma from price on JPY
     *
     * @param \Magento\Framework\Locale\Format $subject
     * @param $value
     * @return array
     */
    public function beforeGetNumber(
        Format $subject,
        $value
    ) {
        $currency = $this->_scopeResolver->getScope()->getCurrentCurrency();
        $locale   = $this->_localeResolver->getLocale();
        $format = $subject->getPriceFormat($locale, $currency->getCode());


        if($format['groupSymbol'] == '.')
        {
            $value = preg_replace('/\./', '', $value);
            $value = preg_replace('/,/', '.', $value);
        } else {
            $value = preg_replace('/,/', '', $value);
        }

        return [$value];
    }
}