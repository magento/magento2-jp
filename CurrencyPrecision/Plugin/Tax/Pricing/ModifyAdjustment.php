<?php
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Plugin\Tax\Pricing;

use CommunityEngineering\CurrencyPrecision\Model\CurrencyRounding;
use \Magento\Tax\Pricing\Adjustment;
use \Magento\Framework\Pricing\SaleableInterface;
use \Magento\Tax\Helper\Data as TaxHelper;
use \Magento\Catalog\Helper\Data;
use \Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Adjust tax rounding against fraction for JPY
 */
class ModifyAdjustment
{
    /**
     * @var TaxHelper
     */
    private $taxHelper;

    /**
     * @var Data
     */
    private $catalogHelper;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var CurrencyRounding
     */
    private $currencyRounding;

    /**
     * @param TaxHelper $taxHelper
     * @param Data $catalogHelper
     * @param PriceCurrencyInterface $currency
     * @param CurrencyRounding $currencyRounding
     */
    public function __construct(
        TaxHelper $taxHelper,
        Data $catalogHelper,
        PriceCurrencyInterface $priceCurrency,
        CurrencyRounding $currencyRounding
    ) {
        $this->taxHelper = $taxHelper;
        $this->catalogHelper = $catalogHelper;
        $this->priceCurrency = $priceCurrency;
        $this->currencyRounding = $currencyRounding;
    }

    /**
     * @param Adjustment $subject
     * @param \Closure $proceed
     * @param $amount
     * @param SaleableInterface $saleableItem
     * @param array $context
     * @return float
     */
    public function aroundExtractAdjustment(
        Adjustment $subject,
        \Closure $proceed,
        $amount,
        SaleableInterface $saleableItem,
        $context = []
    ) {
        if ($this->taxHelper->priceIncludesTax()) {
            $adjustedAmount = $this->catalogHelper->getTaxPrice(
                $saleableItem,
                $amount,
                false,
                null,
                null,
                null,
                null,
                null,
                $this->shouldRound()
            );
            $result = $amount - $adjustedAmount;
        } else {
            $result = 0.;
        }
        return $result;
    }

    /**
     * @param Adjustment $subject
     * @param \Closure $proceed
     * @param $amount
     * @param SaleableInterface $saleableItem
     * @param array $context
     * @return float
     */
    public function aroundApplyAdjustment(
        Adjustment $subject,
        \Closure $proceed,
        $amount,
        SaleableInterface $saleableItem,
        $context = []
    ) {
        return $this->catalogHelper->getTaxPrice(
            $saleableItem,
            $amount,
            true,
            null,
            null,
            null,
            null,
            null,
            $this->shouldRound()
        );
    }

    /**
     * Retrieve should rounding or not.
     *
     * @return bool
     */
    private function shouldRound()
    {
        $currency = $this->priceCurrency->getCurrency();
        $precision = $this->currencyRounding->getPrecision($currency->getCode());
        if ($precision === 0) {
            return true;
        }
        return false;
    }
}
