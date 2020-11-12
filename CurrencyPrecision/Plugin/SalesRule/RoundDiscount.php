<?php
namespace CommunityEngineering\CurrencyPrecision\Plugin\SalesRule;

use CommunityEngineering\CurrencyPrecision\Model\CurrencyRounding;
use Magento\SalesRule\Model\Rule\Action\Discount\DiscountInterface;
use Magento\SalesRule\Model\Rule;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\SalesRule\Model\Rule\Action\Discount\Data;

class RoundDiscount
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
     * @param DiscountInterface $subject
     * @param Data $result
     * @param Rule $rule
     * @param AbstractItem $item
     * @param float $qty
     * @return Data $result
     */
    public function afterCalculate(DiscountInterface $subject, $result, $rule, $item, $qty)
    {
        $quote = $item->getQuote();
        $baseCurrency = $quote->getBaseCurrencyCode();
        $quoteCurrency = $quote->getQuoteCurrencyCode();

        $result->setAmount($this->currencyRounding->round($quoteCurrency, $result->getAmount()));
        $result->setBaseAmount($this->currencyRounding->round($baseCurrency, $result->getBaseAmount()));
        $result->setBaseOriginalAmount($this->currencyRounding->round($baseCurrency, $result->getBaseOriginalAmount()));
        $result->setOriginalAmount($this->currencyRounding->round($quoteCurrency, $result->getOriginalAmount()));

        return $result;
    }
}
