<?php
namespace MagentoJapan\Kana\Plugin\Quote\Model\Quote\Address;

use \Magento\Quote\Model\QuoteAddressValidator;
use \Magento\Quote\Model\Quote\Address;
use \Magento\Customer\Model\AddressFactory;
use \MagentoJapan\Kana\Helper\Data;

class Validator
{
    /**
     * @var \MagentoJapan\Kana\Helper\Data
     */
    private $helper;

    /**
     * @var AddressFactory
     */
    private $factory;

    /**
     * Validator constructor.
     * @param Data $helper
     * @param AddressFactory $factory
     */
    public function __construct(
        Data $helper,
        AddressFactory $factory
    ) {
        $this->helper = $helper;
        $this->factory = $factory;
    }

    /**
     * @param QuoteAddressValidator $subject
     * @param \Magento\Quote\Model\Quote\Address $arguments
     * @return bool
     */
    public function beforeValidate(
        QuoteAddressValidator $subject,
        Address $arguments
    ) {
        if($this->helper->getRequireKana()) {
            $this->checkKana($arguments);
        }
    }


    /**
     * @param Address $address
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    private function checkKana(Address $address)
    {
        $customerAddress = null;
        $fkana = null;
        $lkana = null;

        if(!$address->getQuoteId()) {
            return;
        }

        if($addressId = $address->getCustomerAddressId()) {
            $customerAddress = $this->factory->create()->load($addressId);

            if($fkana = $customerAddress->getFirstnamekana()) {
                $address->setFirstnamekana($fkana);
            }
            if($lkana = $customerAddress->getLastnamekana()) {
                $address->setLastnamekana($lkana);
            }
        }

        if($fkana && !$address->getFirstnamekana())
        {
            throw new \Magento\Framework\Exception\ValidatorException(
                __("Firstname kana is required field. Your address doesn't have it.")
            );
        }

        if($lkana && !$address->getLastnamekana())
        {
            throw new \Magento\Framework\Exception\ValidatorException(
                __("Lastname kana is required field. Your address doesn't have it.")
            );
        }
    }

}