<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Observer\Quote;

use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Transfer kana data from main data object to extension attributes to hide persistence mechanism.
 */
class QuoteKanaFieldsLoader implements ObserverInterface
{
    /**
     * @var CartExtensionFactory
     */
    private $cartExtensionFactory;

    /**
     * QuoteKanaFieldsLoader constructor.
     * @param CartExtensionFactory $cartExtensionFactory
     */
    public function __construct(
        CartExtensionFactory $cartExtensionFactory
    ) {
        $this->cartExtensionFactory = $cartExtensionFactory;
    }

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

        $quoteExtension = $quote->getExtensionAttributes();
        if ($quoteExtension === null) {
            $quoteExtension = $this->cartExtensionFactory->create();
        }

        $quoteExtension->setCustomerFirstnamekana($quote->getData('customer_firstnamekana'));
        $quote->unsetData('customer_firstnamekana');
        $quoteExtension->setCustomerLastnamekana($quote->getData('customer_lastnamekana'));
        $quote->unsetData('customer_lastnamekana');

        $quote->setExtensionAttributes($quoteExtension);
    }
}
