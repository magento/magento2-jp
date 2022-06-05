<?php

namespace CommunityEngineering\JapaneseAddress\Plugin\Customer\Model\Customer;

use Magento\Eav\Model\Config;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Class DataProviderWithDefaultAddresses
 *
 * @package CommunityEngineering\JapaneseAddress\Plugin\Customer\Model\Customer
 */
class DataProviderWithDefaultAddresses
{
    /**
     * @var \Magento\Customer\Helper\Address
     */
    protected $addressHelper;

    /**
     * @var ResolverInterface
     */
    private $localeResolver;

    /**
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     * @param \Magento\Customer\Helper\Address $addressHelper
     */
    public function __construct(
        ResolverInterface $localeResolver,
        \Magento\Customer\Helper\Address $addressHelper
    ) {
        $this->localeResolver = $localeResolver;
        $this->addressHelper = $addressHelper;
    }

    /**
     * @param \Magento\Customer\Model\Customer\DataProviderWithDefaultAddresses $subject
     * @param array $result
     *
     * @return array
     * @see \Magento\Customer\Model\Customer\DataProviderWithDefaultAddresses::getData
     */
    public function afterGetData(\Magento\Customer\Model\Customer\DataProviderWithDefaultAddresses $subject, array $result)
    {
        if ($this->localeResolver->getLocale() !== 'ja_JP') {
            return $result;
        }

        foreach ($result as $customerId => $customer) {
            $defaultBillingAddressFormatContent = null;
            $defaultShippingAddressFormatContent = null;
            if (isset($customer['default_billing_address']) && !empty($customer['default_billing_address'])) {
                $defaultBillingAddressFormatContent = $this->addressHelper->getFormatTypeRenderer('html')->renderArray($customer['default_billing_address']);
            }

            if (isset($customer['default_shipping_address']) && !empty($customer['default_shipping_address'])) {
                $defaultShippingAddressFormatContent = $this->addressHelper->getFormatTypeRenderer('html')->renderArray($customer['default_shipping_address']);
            }

            $result[$customerId]['default_billing_address']['address_format_content'] = $defaultBillingAddressFormatContent;
            $result[$customerId]['default_shipping_address']['address_format_content'] = $defaultShippingAddressFormatContent;
        }

        return $result;
    }
}
