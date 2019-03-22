<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Observer\Quote;

use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Transfer kana data from extension attributes to main data object to use regular persistence mechanism.
 */
class QuoteAddressKanaFieldsSaver implements ObserverInterface
{
    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $address = $observer->getData('quote_address');
        if (!$address instanceof AddressInterface) {
            throw new \LogicException('Observer should be bound to event with quote address.');
        }

        $addressExtension = $address->getExtensionAttributes();
        if ($addressExtension === null) {
            return;
        }

        if (!$address instanceof DataObject) {
            throw new \LogicException('Current Kana implementation expects quote address to be data object.');
        }

        if (!$address->hasData('firstnamekana')) {
            $address->setData('firstnamekana', $addressExtension->getFirstnamekana());
        }
        if (!$address->hasData('lastnamekana')) {
            $address->setData('lastnamekana', $addressExtension->getLastnamekana());
        }
    }
}
