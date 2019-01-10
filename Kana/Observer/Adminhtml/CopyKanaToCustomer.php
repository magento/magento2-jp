<?php
declare(strict_types=1);

namespace MagentoJapan\Kana\Observer\Adminhtml;

use Magento\Framework\Locale\Currency;
use Magento\Framework\Event\ObserverInterface;

/**
 * Copy kana fields to Customer entity.
 */
class CopyKanaToCustomer implements ObserverInterface
{
    /**
     * Assign Kana to destination obj
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
        $customer = $observer->getEvent()->getCustomer();
        $request = $observer->getEvent()->getRequest();

        $posted = $request->getParam('customer');

        $customer->setCustomAttribute('firstnamekana', $posted['firstnamekana']);
        $customer->setCustomAttribute('lastnamekana', $posted['lastnamekana']);

        return $this;
    }
}
