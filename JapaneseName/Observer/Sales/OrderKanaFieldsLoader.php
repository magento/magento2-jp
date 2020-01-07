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
use Magento\Sales\Api\Data\OrderExtensionFactory;

/**
 * Transfer kana data from main data object to extension attributes to hide persistence mechanism.
 */
class OrderKanaFieldsLoader implements ObserverInterface
{
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * OrderKanaFieldsLoader constructor.
     * @param OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
    }
    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getData('order');
        if ($order) {
            $this->loadKana($order);
            return;
        }

        $orderCollection = $observer->getData('order_collection');
        if ($orderCollection) {
            foreach ($orderCollection as $order) {
                $this->loadKana($order);
            }
            return;
        }

        throw new \LogicException('Observer should be bound to event with order.');
    }

    /**
     * Set kana extension attributes for top-level container.
     *
     * @param OrderInterface $order
     */
    private function loadKana(OrderInterface $order)
    {
        if (!$order instanceof DataObject) {
            throw new \LogicException('Current Kana implementation expects order to be data object.');
        }

        $orderExtension = $order->getExtensionAttributes();
        if ($orderExtension === null) {
            $orderExtension = $this->orderExtensionFactory->create();
        }

        if ($order->hasData('customer_firstnamekana')) {
            $orderExtension->setCustomerFirstnamekana($order->getData('customer_firstnamekana'));
            $order->unsetData('customer_firstnamekana');
        }
        if ($order->hasData('customer_lastnamekana')) {
            $orderExtension->setCustomerLastnamekana($order->getData('customer_lastnamekana'));
            $order->unsetData('customer_lastnamekana');
        }

        $order->setExtensionAttributes($orderExtension);
    }
}
