<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Plugin\Directory\Model;

use Magento\Directory\Model\Currency;
use CommunityEngineering\CurrencyPrecision\Model\CurrencyRounding;

/**
 * Add currency precision option for formatting.
 */
class CurrencyPrecisionFormatting
{
    /**
     * System Configuration.
     *
     * @var CurrencyRounding
     */
    private $currencyRounding;

    /**
     * @param CurrencyRounding $currencyRounding
     */
    public function __construct(
        CurrencyRounding $currencyRounding
    ) {
        $this->currencyRounding = $currencyRounding;
    }

    /**
     * Override requested precision to use system defined precision.
     *
     * Only Currency::formatTxt should be pluginized as all other formatting methods should use its result.
     *
     * @param Currency $currency
     * @param float|string $price
     * @param array $options
     * @return array
     */
    public function beforeFormatTxt(
        Currency $currency,
        $price,
        $options = []
    ) {
        //round before formatting to apply configured rounding mode.
        if ($currency->getCode() !== null) {
            $price = $this->currencyRounding->round($currency->getCode(), (float)$price);
            $options['precision'] = $this->currencyRounding->getPrecision($currency->getCode());
        }
        return [$price, $options];
    }
}
