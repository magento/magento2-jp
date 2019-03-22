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
 * Transfer kana data from extension attributes to main data object to use regular persistence mechanism.
 */
class QuoteKanaFieldsSaver implements ObserverInterface
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

        $quoteExtension = $quote->getExtensionAttributes();
        if ($quoteExtension === null) {
            return;
        }

        if (!$quote instanceof DataObject) {
            throw new \LogicException('Current Kana implementation expects quote to be data object.');
        }

        if (!$quote->hasData('customer_firstnamekana')) {
            $quote->setData('customer_firstnamekana', $quoteExtension->getCustomerFirstnamekana());
        }
        if (!$quote->hasData('customer_lastnamekana')) {
            $quote->setData('customer_lastnamekana', $quoteExtension->getCustomerLastnamekana());
        }
    }
}
