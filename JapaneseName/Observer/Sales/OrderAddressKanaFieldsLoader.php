<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Observer\Sales;

use Magento\Framework\DataObject;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Api\Data\OrderAddressExtensionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Transfer kana data from main data object to extension attributes to hide persistence mechanism.
 */
class OrderAddressKanaFieldsLoader implements ObserverInterface
{
    /**
     * @var OrderAddressExtensionFactory
     */
    private $addressExtensionFactory;

    /**
     * @param OrderAddressExtensionFactory $addressExtensionFactory
     */
    public function __construct(OrderAddressExtensionFactory $addressExtensionFactory)
    {
        $this->addressExtensionFactory = $addressExtensionFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $address = $observer->getData('address');
        if ($address) {
            $this->loadKana($address);
            return;
        }

        $addressCollection = $observer->getData('order_address_collection');
        if (isset($addressCollection)) {
            foreach ($addressCollection as $address) {
                $this->loadKana($address);
            }
            return;
        }

        throw new \LogicException('Observer should be bound to event with order address.');
    }

    /**
     * Set kana extension attributes for top-level container.
     *
     * @param OrderAddressInterface $address
     */
    private function loadKana(OrderAddressInterface $address)
    {
        if (!$address instanceof DataObject) {
            throw new \LogicException('Current Kana implementation expects order address to be data object.');
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
