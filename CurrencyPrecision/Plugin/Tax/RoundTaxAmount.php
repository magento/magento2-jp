<?php
namespace CommunityEngineering\CurrencyPrecision\Plugin\Tax;

use CommunityEngineering\CurrencyPrecision\Model\CurrencyRounding;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Tax\Model\Sales\Total\Quote\Tax;

class RoundTaxAmount
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
     * @param \Magento\Tax\Model\Sales\Total\Quote\Tax $subject
     * @param $result
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     */
    public function afterCollect(
        \Magento\Tax\Model\Sales\Total\Quote\Tax $subject,
        $result,
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        $baseCurrency = $quote->getBaseCurrencyCode();
        if ($baseCurrency === null) {
            $baseCurrency = $quote->getStore()->getBaseCurrencyCode();
        }

        $total->setBaseTotalAmount('subtotal', $this->round($baseCurrency, $total->getBaseTotalAmount('subtotal')));
        $total->setBaseTotalAmount('tax', $this->round($baseCurrency, $total->getBaseTotalAmount('tax')));
        $total->setBaseTotalAmount('discount_tax_compensation', $this->round($baseCurrency, $total->getBaseTotalAmount('discount_tax_compensation')));

        $total->setBaseTaxAmount($this->round($baseCurrency, $total->getBaseTaxAmount()));
        $total->setBaseSubtotalTotalInclTax(
            $this->round($baseCurrency, $total->getBaseSubtotalTotalInclTax())
        );
        $total->setBaseSubtotalInclTax($this->round($baseCurrency, $total->getBaseSubtotalInclTax()));

        $address = $shippingAssignment->getShipping()->getAddress();
        $address->setBaseSubtotalTotalInclTax($total->getBaseSubtotalTotalInclTax());
        $address->setBaseSubtotal($total->getBaseSubtotal());
        $address->setBaseTaxAmount($total->getBaseTaxAmount());

        $total->getAppliedTaxes();

        return $result;
    }

    private function round($code, $amount)
    {
        return $this->currencyRounding->round($code, (float)$amount);
    }
}
