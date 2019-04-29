<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseYenFormatting\Plugin\Directory\Model;

use Magento\Directory\Model\Currency;
use CommunityEngineering\JapaneseYenFormatting\Model\CurrencyFormatOptionModifiers;

/**
 * Apply Japanese Yen formatting for currency.
 */
class YenFormatting
{
    /**
     * @var CurrencyFormatOptionModifiers
     */
    private $currencyFormatOptionModifiers;

    /**
     * @param CurrencyFormatOptionModifiers $currencyFormatOptionModifiers
     */
    public function __construct(
        CurrencyFormatOptionModifiers $currencyFormatOptionModifiers
    ) {
        $this->currencyFormatOptionModifiers = $currencyFormatOptionModifiers;
    }

    /**
     * Add formatting options before format currency value.
     *
     * Only Currency::formatTxt should be pluginized as all other formatting methods should use its result.
     *
     * @param Currency $currency
     * @param float|string $price
     * @param array $originalOptions
     * @return array
     */
    public function beforeFormatTxt(
        Currency $currency,
        $price,
        $originalOptions = []
    ) {
        $modifiers = $this->currencyFormatOptionModifiers->getOptions((string)$currency->getCode());
        $optionsToApply = array_merge($originalOptions, $modifiers);
        return [$price, $optionsToApply];
    }
}
