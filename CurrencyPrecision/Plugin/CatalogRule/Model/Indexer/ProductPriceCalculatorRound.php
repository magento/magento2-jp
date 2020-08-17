<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Plugin\CatalogRule\Model\Indexer;

use Magento\CatalogRule\Model\Indexer\ProductPriceCalculator;
use Magento\Directory\Model\PriceCurrency;
use Magento\Store\Model\StoreManager;

class ProductPriceCalculatorRound
{
    private $storeManager;

    private $priceCurrency;

    public function __construct(
        StoreManager $storeManager,
        PriceCurrency $priceCurrency
    ) {
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
    }

    public function beforeCalculate(
        ProductPriceCalculator $calculator,
        $ruleData,
        $productData = null
    ) {
        $website = $this->storeManager->getWebsite($ruleData['website_id']);
        $currency = $this->priceCurrency->getCurrency($website->getDefaultStore()->getId());
        return [$ruleData, $productData];
    }
}
