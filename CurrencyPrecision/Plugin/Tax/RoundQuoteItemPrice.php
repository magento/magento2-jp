<?php
namespace CommunityEngineering\CurrencyPrecision\Plugin\Tax;

use CommunityEngineering\CurrencyPrecision\Model\CurrencyRounding;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Store\Model\Store;
use Magento\Tax\Api\Data\TaxDetailsItemInterface;
use Magento\Tax\Model\Sales\Total\Quote\CommonTaxCollector;

class RoundQuoteItemPrice
{
    /**
     * @var CurrencyRounding
     */
    private $currencyRounding;

    /**
     * RoundDiscount constructor.
     * @param CurrencyRounding $currencyRounding
     */
    public function __construct(
        CurrencyRounding $currencyRounding
    ) {
        $this->currencyRounding = $currencyRounding;
    }

    /**
     * @param CommonTaxCollector $subject
     * @param CommonTaxCollector $result
     * @param AbstractItem $quoteItem
     * @param TaxDetailsItemInterface $itemTaxDetails
     * @param TaxDetailsItemInterface $baseItemTaxDetails
     * @param Store $store
     */
    public function afterUpdateItemTaxInfo(
        CommonTaxCollector $subject,
        CommonTaxCollector $result,
        AbstractItem $quoteItem,
        TaxDetailsItemInterface $itemTaxDetails,
        TaxDetailsItemInterface $baseItemTaxDetails,
        Store $store
    ) {
        $quote = $quoteItem->getQuote();
        $baseCurrency = $quote->getBaseCurrencyCode();
        if ($baseCurrency === null) {
            $baseCurrency = $quote->getStore()->getBaseCurrencyCode();
        }

        $quoteItem->setBasePrice($this->round($quoteItem->getBasePrice(), $baseCurrency));
        $quoteItem->setBasePriceInclTax($this->round($quoteItem->getBasePriceInclTax(), $baseCurrency));
        $quoteItem->setBaseRowTotal($this->round($quoteItem->getBaseRowTotal(), $baseCurrency));
        $quoteItem->setBaseRowTotalInclTax($this->round($quoteItem->getBaseRowTotalInclTax(), $baseCurrency));
        $quoteItem->setBaseTaxAmount($this->round($quoteItem->getBaseTaxAmount(), $baseCurrency));
        $quoteItem->setTaxPercent($baseItemTaxDetails->getTaxPercent());
        //$quoteItem->setBaseDiscountTaxCompensationAmount($baseItemTaxDetails->getDiscountTaxCompensationAmount());

        return $result;
    }

    private function round($amount, $currency)
    {
        return $this->currencyRounding->round($currency, $amount);
    }
}
