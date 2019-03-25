<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Observer\Sales;

use Magento\Framework\DataObject;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Transfer kana data from extension attributes to main data object to use regular persistence mechanism.
 */
class OrderKanaFieldsSaver implements ObserverInterface
{
    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getData('order');
        if (!$order instanceof OrderInterface) {
            throw new \LogicException('Observer should be bound to event with order.');
        }

        $orderExtension = $order->getExtensionAttributes();
        if ($orderExtension === null) {
            return;
        }

        if (!$order instanceof DataObject) {
            throw new \LogicException('Current Kana implementation expects order to be data object.');
        }

        if (!$order->hasData('customer_firstnamekana')) {
            $order->setData('customer_firstnamekana', $orderExtension->getCustomerFirstnamekana());
        }
        if (!$order->hasData('customer_lastnamekana')) {
            $order->setData('customer_lastnamekana', $orderExtension->getCustomerLastnamekana());
        }
    }
}
