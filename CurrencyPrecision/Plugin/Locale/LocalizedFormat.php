<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Plugin\Locale;

use Magento\Framework\Locale\Format;
use Magento\Framework\Locale\Resolver;

/**
 * Plugin for implement correct locale aware number parsing.
 */
class LocalizedFormat
{
    /**
     * @var Resolver
     */
    private $localeResolver;

    /**
     * @param Resolver $localeResolver
     */
    public function __construct(Resolver $localeResolver)
    {
        $this->localeResolver = $localeResolver;
    }

    /**
     * Parse number with locale-aware parser.
     *
     * @param Format $format
     * @param mixed $value
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeGetNumber(Format $format, $value)
    {
        if (!is_string($value)) {
            return [$value];
        }

        $formatter = new \NumberFormatter($this->localeResolver->getLocale(), \NumberFormatter::DECIMAL);
        $number = $formatter->parse($value);
        return [(string)$number]; // trigger core logic with dot handling for backward compatibility
    }
}
