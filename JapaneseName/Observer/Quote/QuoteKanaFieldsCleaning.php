<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Observer\Quote;

use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Ensure that after load kana data available only as extension attributes.
 */
class QuoteKanaFieldsCleaning implements ObserverInterface
{
    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $quote = $observer->getData('quote');
        if (!$quote instanceof CartInterface) {
            throw new \LogicException('Observer should be bound to event with quote.');
        }
        if (!$quote instanceof DataObject) {
            throw new \LogicException('Current Kana implementation expects quote to be data object.');
        }

        $quote->unsetData('customer_firstnamekana');
        $quote->unsetData('customer_lastnamekana');
    }
}
