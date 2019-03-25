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
 * Ensure that after load kana data available only as extension attributes.
 */
class OrderKanaFieldsCleaning implements ObserverInterface
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
        if (!$order instanceof DataObject) {
            throw new \LogicException('Current Kana implementation expects order to be data object.');
        }

        $order->unsetData('customer_firstnamekana');
        $order->unsetData('customer_lastnamekana');
    }
}
