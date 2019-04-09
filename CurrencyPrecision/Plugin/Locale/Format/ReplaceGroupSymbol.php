<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Plugin\Locale\Format;

use Magento\Framework\Locale\Format;
use Magento\Framework\App\ScopeResolverInterface;
use Magento\Framework\Locale\ResolverInterface;

class ReplaceGroupSymbol
{
    /**
     * Scope Resolver
     *
     * @var \Magento\Framework\App\ScopeResolverInterface
     */
    private $scopeResolver;

    /**
     * Locale Resolver
     *
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $localeResolver;

    /**
     * currency format cache
     *
     * @var arrays
     */
    private static $cache;

    /**
     * Constructor
     *
     * @param ScopeResolverInterface $scopeResolver   Scope Resolver
     * @param ResolverInterface      $localeResolver  Locale Resolver
     */
    public function __construct(
        ScopeResolverInterface $scopeResolver,
        ResolverInterface $localeResolver
    ) {
        $this->scopeResolver = $scopeResolver;
        $this->localeResolver = $localeResolver;
    }

    /**
     * @param Format $format
     * @param $value
     * @return array
     */
    public function beforeGetNumber(
        Format $format,
        $value
    ) {
        if (!is_string($value)) {
            return [$value];
        }

        $currency = $this->scopeResolver->getScope()->getCurrentCurrency();
        $locale   = $this->localeResolver->getLocale();
        $currencyCode = $currency->getCode();
        $formatCode = $locale . '_' . $currencyCode;

        if(!isset(self::$cache[$formatCode])) {
            self::$cache[$formatCode] = $format->getPriceFormat($locale, $currencyCode);
        }

        if(self::$cache[$formatCode]['groupSymbol'] == '.')
        {
            $value = preg_replace('/\./', '', $value);
            $value = preg_replace('/,/', '.', $value);
        } else {
            $value = preg_replace('/,/', '', $value);
        }

        return [$value];
    }
}