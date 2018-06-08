<?php
namespace MagentoJapan\Kana\Observer;

use Magento\Framework\Locale\Currency;
use Magento\Framework\Event\ObserverInterface;

class CopyKanaToOrder implements ObserverInterface
{
    /**
     * Assign Kana to destination obj
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $order = $observer->getEvent()->getOrder();

        $fKana = $quote->getCustomerFirstnamekana();
        $lKana = $quote->getCustomerLastnamekana();

        $order->setCustomerFirstnamekana($fKana);
        $order->setCustomerLastnamekana($lKana);

        return $this;
    }
}
