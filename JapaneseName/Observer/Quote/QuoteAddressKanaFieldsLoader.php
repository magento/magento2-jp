<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Observer\Quote;

use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\AddressExtensionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Transfer kana data from main data object to extension attributes to hide persistence mechanism.
 */
class QuoteAddressKanaFieldsLoader implements ObserverInterface
{
    /**
     * @var AddressExtensionFactory
     */
    private $addressExtensionFactory;

    /**
     * @param AddressExtensionFactory $addressExtensionFactory
     */
    public function __construct(AddressExtensionFactory $addressExtensionFactory)
    {
        $this->addressExtensionFactory = $addressExtensionFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $address = $observer->getData('quote_address');
        if ($address) {
            $this->loadKana($address);
            return;
        }

        $addressCollection = $observer->getData('quote_address_collection');
        if (isset($addressCollection)) {
            foreach ($addressCollection as $address) {
                $this->loadKana($address);
            }
            return;
        }

        throw new \LogicException('Observer should be bound to event with quote address.');
    }

    /**
     * Set kana extension attributes for top-level container.
     *
     * @param AddressInterface $address
     */
    private function loadKana(AddressInterface $address)
    {

        if (!$address instanceof DataObject) {
            throw new \LogicException('Current Kana implementation expects quote address to be data object.');
        }

        $addressExtension = $address->getExtensionAttributes();
        if ($addressExtension === null) {
            $addressExtension = $this->addressExtensionFactory->create();
        }

        if ($address->hasData('firstnamekana')) {
            $addressExtension->setFirstnamekana($address->getData('firstnamekana'));
            $address->unsetData('firstnamekana');
        }
        if ($address->hasData('lastnamekana')) {
            $addressExtension->setLastnamekana($address->getData('lastnamekana'));
            $address->unsetData('lastnamekana');
        }

        $address->setExtensionAttributes($addressExtension);
    }
}
