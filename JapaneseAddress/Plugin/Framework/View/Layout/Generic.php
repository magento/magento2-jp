<?php

namespace CommunityEngineering\JapaneseAddress\Plugin\Framework\View\Layout;

use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\View\Element\UiComponentInterface;

/**
 * Class Generic
 *
 * @package CommunityEngineering\JapaneseAddress\Plugin\Framework\View\Layout
 */
class Generic
{
    public const JP_ADDRESS_FILE = 'CommunityEngineering_JapaneseAddress/jp-address.html';

    /**
     * @var ResolverInterface
     */
    protected $localeResolver;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     * @param \Magento\Framework\Stdlib\ArrayManager $arrayManager
     */
    public function __construct(
        ResolverInterface $localeResolver,
        ArrayManager $arrayManager
    ) {
        $this->localeResolver = $localeResolver;
        $this->arrayManager = $arrayManager;
    }

    /**
     * @param \Magento\Framework\View\Layout\Generic $subject
     * @param array $result
     * @param \Magento\Framework\View\Element\UiComponentInterface $component
     *
     * @return array
     */
    public function afterBuild(\Magento\Framework\View\Layout\Generic $subject, array $result, UiComponentInterface $component)
    {
        if ($this->localeResolver->getLocale() !== 'ja_JP' || $component->getName() !== 'customer_form') {
            return $result;
        }

        $customerDefaultBillingAddressContent = $this->arrayManager->findPath('customer_default_billing_address_content', $result);
        $customerDefaultShippingAddressContent = $this->arrayManager->findPath('customer_default_shipping_address_content', $result);
        $customerDefaultBillingAddressContentTemplate = $this->arrayManager->findPath('template', $result, $customerDefaultBillingAddressContent);
        $customerDefaultShippingAddressContentTemplate = $this->arrayManager->findPath('template', $result, $customerDefaultShippingAddressContent);
        if ($customerDefaultBillingAddressContent && $customerDefaultBillingAddressContentTemplate) {
            $result = $this->arrayManager->set($customerDefaultBillingAddressContentTemplate, $result, self::JP_ADDRESS_FILE);
        }

        if ($customerDefaultShippingAddressContent && $customerDefaultShippingAddressContentTemplate) {
            $result = $this->arrayManager->set($customerDefaultShippingAddressContentTemplate, $result, self::JP_ADDRESS_FILE);
        }

        return $result;
    }
}
