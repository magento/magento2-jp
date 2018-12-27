<?php
declare(strict_types=1);

namespace MagentoJapan\Kana\Observer;

use Magento\Framework\Locale\Currency;
use Magento\Framework\Event\ObserverInterface;

/**
 * Copy kana fields to Order entity.
 */
class CopyKanaToOrder implements ObserverInterface
{
    /**
     * Assign Kana to destination object.
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
