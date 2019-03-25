<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Observer\Sales;

use Magento\Framework\DataObject;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Transfer kana data from extension attributes to main data object to use regular persistence mechanism.
 */
class OrderAddressKanaFieldsSaver implements ObserverInterface
{
    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $address = $observer->getData('address');
        if (!$address instanceof OrderAddressInterface) {
            throw new \LogicException('Observer should be bound to event with order address.');
        }

        $addressExtension = $address->getExtensionAttributes();
        if ($addressExtension === null) {
            return;
        }

        if (!$address instanceof DataObject) {
            throw new \LogicException('Current Kana implementation expects order address to be data object.');
        }

        if (!$address->hasData('firstnamekana')) {
            $address->setData('firstnamekana', $addressExtension->getFirstnamekana());
        }
        if (!$address->hasData('lastnamekana')) {
            $address->setData('lastnamekana', $addressExtension->getLastnamekana());
        }
    }
}
