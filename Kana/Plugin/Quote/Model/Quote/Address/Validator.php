<?php
namespace MagentoJapan\Kana\Plugin\Quote\Model\Quote\Address;

use Magento\Customer\Model\AddressFactory;
use Magento\Quote\Model\QuoteAddressValidator;
use MagentoJapan\Kana\Model\Config\System;
use Magento\Quote\Api\Data\AddressInterface;

/**
 * Validate Customer's name in Kana.
 */
class Validator
{
    /**
     * @var \MagentoJapan\Kana\Model\Config\System
     */
    private $system;

    /**
     * @var AddressFactory
     */
    private $factory;

    /**
     * @param System $system
     * @param AddressFactory $factory
     */
    public function __construct(
        System $system,
        AddressFactory $factory
    ) {
        $this->system = $system;
        $this->factory = $factory;
    }

    /**
     * Validate Customer's name in Kana.
     *
     * @param QuoteAddressValidator $subject
     * @param AddressInterface $arguments
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeValidate(
        QuoteAddressValidator $subject,
        AddressInterface $arguments
    ) {
        if ($this->system->getRequireKana()) {
            $this->checkKana($arguments);
        }
    }

    /**
     * Validate Customer's name in Kana.
     *
     * @param AddressInterface $address
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    private function checkKana(AddressInterface $address)
    {
        $customerAddress = null;
        $fkana = null;
        $lkana = null;

        if (!$address->getQuoteId()) {
            return;
        }

        if ($addressId = $address->getCustomerAddressId()) {
            $customerAddress = $this->factory->create()->load($addressId);

            if ($fkana = $customerAddress->getFirstnamekana()) {
                $address->setFirstnamekana($fkana);
            }
            if ($lkana = $customerAddress->getLastnamekana()) {
                $address->setLastnamekana($lkana);
            }
        }

        if ($fkana && !$address->getFirstnamekana()) {
            throw new \Magento\Framework\Exception\ValidatorException(
                __("Firstname kana is required field. Your address doesn't have it.")
            );
        }

        if ($lkana && !$address->getLastnamekana()) {
            throw new \Magento\Framework\Exception\ValidatorException(
                __("Lastname kana is required field. Your address doesn't have it.")
            );
        }
    }
}
