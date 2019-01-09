<?php
declare(strict_types=1);

namespace MagentoJapan\Kana\Plugin\Checkout\Block\Checkout;

use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Model\Session;
use MagentoJapan\Kana\Model\Config\System;

/**
 * Extending the Checkout layout with Kana fields.
 */
class LayoutProcessor
{
    /**
     * @var string
     */
    const CONFIG_ELEMENT_ORDER = 'localize/sort/';

    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterface
     */
    private $customer;

    /**
     * @var System
     */
    private $system;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param System $system
     */
    public function __construct(
        Session $customerSession,
        CustomerRepository $customerRepository,
        System $system
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->system = $system;
    }

    /**
     * Extending the Checkout layout with Kana fields.
     *
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        $format = $this->system->getSortOrder();

        if (!$format) {
            return $jsLayout;
        }

        $shippingElements =& $jsLayout['components']['checkout']['children']
        ['steps']['children']['shipping-step']['children']['shippingAddress']
        ['children']['shipping-address-fieldset']['children'];

        $this->processShipping($shippingElements);

        $payments =& $jsLayout['components']['checkout']['children']
        ['steps']['children']['billing-step']['children']['payment']
        ['children']['payments-list']['children'];

        $this->processBilling($payments);

        return $jsLayout;
    }

    /**
     * Get Customer entity.
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
    private function getCustomer()
    {
        if (!$this->customer) {
            $session = $this->customerSession;
            if (!$session->isLoggedIn()) {
                return null;
            }
            $this->customer = $this->customerRepository->getById($session->getCustomerId());
        }

        return $this->customer;
    }

    /**
     * Process shipping elements layout Kana fields.
     *
     * @param array $shippingElements
     * @return void
     */
    private function processShipping(&$shippingElements)
    {
        $hideCountry = $this->system->getShowCountry();

        foreach ($shippingElements as $key => &$shippingElement) {
            if ($key === 'region_id') {
                $key = 'region';
            }
            $path = self::CONFIG_ELEMENT_ORDER . $key;
            $config = $this->system->getConfigValue($path);
            $shippingElement['sortOrder'] = $config;

            if ($key === 'country_id' && $hideCountry) {
                $shippingElement['visible'] = false;
            }

            if (in_array($key, ['firstnamekana', 'lastnamekana']) && $this->getCustomer()) {
                $attribute = $this->getCustomer()->getCustomAttribute($key);
                if (is_object($attribute)) {
                    $shippingElement['value'] = $attribute->getValue();
                }
                $this->processKanaFieldsVisibility($shippingElement);
            }
        }
    }

    /**
     * Process Billing address Kana names.
     *
     * @param array $payments
     * @return void
     */
    private function processBilling(&$payments)
    {
        foreach ($payments as $key => &$method) {
            if (!isset($method['dataScopePrefix'])) {
                $method['dataScopePrefix'] = $key;
            }
            $elements =& $method['children']['form-fields']['children'];
            if (!is_array($elements)) {
                $elements = [];
                continue;
            }
            foreach ($elements as $billingElementKey => &$billingElement) {
                $this->processBillingElement($billingElementKey, $billingElement);
            }
        }
    }

    /**
     * Process each payment method billing address in the layout.
     *
     * @param string $billingElementKey
     * @param array $billingElement
     */
    private function processBillingElement(string $billingElementKey, array &$billingElement)
    {
        $hideCountry = $this->system->getShowCountry();

        if ($billingElementKey === 'region_id') {
            $billingElementKey = 'region';
        }
        $path = self::CONFIG_ELEMENT_ORDER . $billingElementKey;
        $config = $this->system->getConfigValue($path);
        $billingElement['sortOrder'] = $config;

        if ($billingElementKey === 'country_id' && $hideCountry) {
            $billingElement['visible'] = false;
        }

        if (in_array($billingElementKey, ['firstnamekana', 'lastnamekana']) && $this->getCustomer()) {
            $attribute = $this->getCustomer()->getCustomAttribute($billingElementKey);
            if (is_object($attribute)) {
                $billingElement['value'] = $attribute->getValue();
            }
            $this->processKanaFieldsVisibility($billingElement);
        }
    }

    /**
     * Process Kana fields visibility configuration.
     *
     * @param array $element
     */
    private function processKanaFieldsVisibility(array &$element)
    {
        $useKana = $this->system->getUseKana();
        $requireKana = $this->system->getRequireKana();

        if ($useKana != '1') {
            $element['visible'] = false;
        }
        if ($useKana && $requireKana) {
            $element['validation']['required-entry'] = true;
        }
    }
}
