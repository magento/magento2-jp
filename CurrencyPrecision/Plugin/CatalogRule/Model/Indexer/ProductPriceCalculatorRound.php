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

/**
 * Set currency object for adminhtml side currency rounding.
 */
class ProductPriceCalculatorRound
{
    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @var PriceCurrency
     */
    private $priceCurrency;

    /**
     * ProductPriceCalculatorRound constructor.
     * @param StoreManager $storeManager
     * @param PriceCurrency $priceCurrency
     */
    public function __construct(
        StoreManager $storeManager,
        PriceCurrency $priceCurrency
    ) {
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Extract currency object and pass to CurrencyRoundingForAdmin
     *
     * @param ProductPriceCalculator $calculator
     * @param $ruleData
     * @param null $productData
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @see \CommunityEngineering\CurrencyPrecision\Plugin\Directory\Model\CurrencyRoundingForAdmin
     */
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
