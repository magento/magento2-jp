<?php
declare(strict_types=1);

namespace MagentoJapan\Price\Model\Locale\Plugin;

use Magento\Framework\Locale\Format;
use \Magento\Framework\App\ScopeResolverInterface;
use \Magento\Framework\Locale\ResolverInterface;
use \Magento\Directory\Model\CurrencyFactory;

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

    private static $format;

    /**
     * @param ScopeResolverInterface $scopeResolver Scope Resolver
     * @param ResolverInterface $localeResolver Locale Resolver
     * @param CurrencyFactory $currencyFactory Currency Resolver
     */
    public function __construct(
        ScopeResolverInterface $scopeResolver,
        ResolverInterface $localeResolver,
        CurrencyFactory $currencyFactory
    ) {
        $this->_scopeResolver = $scopeResolver;
        $this->_localeResolver = $localeResolver;
        $this->_currencyFactory = $currencyFactory;
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
        $locale = $this->_localeResolver->getLocale();
        $currencyCode = $currency->getCode();
        $formatCode = $locale . '_' . $currencyCode;

        if (!isset(self::$format[$formatCode])) {
            self::$format[$formatCode] = $subject->getPriceFormat($locale, $currencyCode);
        }

        if (self::$format[$formatCode]['groupSymbol'] == '.') {
            $value = preg_replace('/\./', '', $value);
            $value = preg_replace('/,/', '.', $value);
        } else {
            $value = preg_replace('/,/', '', $value);
        }

        return [$value];
    }
}
