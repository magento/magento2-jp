<?php
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

        $hideCountry = $this->system->getShowCountry();
        $useKana = $this->system->getUseKana();
        $requireKana = $this->system->getRequireKana();

        if ($format) {
            $shippingElements =& $jsLayout['components']['checkout']['children']
            ['steps']['children']['shipping-step']['children']['shippingAddress']
            ['children']['shipping-address-fieldset']['children'];

            foreach ($shippingElements as $key => &$shippingelement) {
                if ($key == 'region_id') {
                    $key = 'region';
                }
                $path = self::CONFIG_ELEMENT_ORDER . $key;
                $config = $this->system->getConfigValue($path);
                $shippingelement['sortOrder'] = $config;

                if ($key == 'country_id' && $hideCountry) {
                    $shippingelement['visible'] = false;
                }

                if (in_array($key, ['firstnamekana', 'lastnamekana'])) {
                    if ($this->getCustomer()) {
                        $attribute = $this->getCustomer()
                            ->getCustomAttribute($key);
                        if (is_object($attribute)) {
                            $shippingelement['value'] = $attribute->getValue();
                            if ($useKana != '1') {
                                $shippingelement['visible'] = false;
                            }
                            if ($useKana && $requireKana) {
                                $shippingelement['validation']['required-entry'] = true;
                            }
                        }
                    } else {
                        if ($useKana != '1') {
                            $shippingelement['visible'] = false;
                        }
                        if ($useKana && $requireKana) {
                            $shippingelement['validation']['required-entry'] = true;
                        }
                    }
                }
            }

            $payments =& $jsLayout['components']['checkout']['children']
            ['steps']['children']['billing-step']['children']['payment']
            ['children']['payments-list']['children'];

            foreach ($payments as $_key => &$method) {
                if (!isset($method['dataScopePrefix'])) {
                    $method['dataScopePrefix'] = $_key;
                }
                $elements =& $method['children']['form-fields']['children'];
                if (!is_array($elements)) {
                    $elements = [];
                    continue;
                }
                foreach ($elements as $key => &$billingElement) {
                    if ($key == 'region_id') {
                        $key = 'region';
                    }
                    $path = self::CONFIG_ELEMENT_ORDER . $key;
                    $config = $this->system->getConfigValue($path);
                    $billingElement['sortOrder'] = $config;

                    if ($key == 'country_id' && $hideCountry) {
                        $billingElement['visible'] = false;
                    }

                    if (in_array($key, ['firstnamekana', 'lastnamekana'])) {
                        if ($this->getCustomer()) {
                            $attribute = $this->getCustomer()
                                ->getCustomAttribute($key);
                            if (is_object($attribute)) {
                                $billingElement['value'] = $attribute->getValue();
                                if ($useKana != '1') {
                                    $billingElement['visible'] = false;
                                }
                                if ($useKana && $requireKana) {
                                    $billingElement['validation']['required-entry'] = true;
                                }
                            }
                        } else {
                            if ($useKana != '1') {
                                $billingElement['visible'] = false;
                            }
                            if ($useKana && $requireKana) {
                                $billingElement['validation']['required-entry'] = true;
                            }
                        }
                    }
                }
            }
        }
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
            $_session = $this->customerSession;
            if ($_session->isLoggedIn()) {
                $this->customer = $this->customerRepository
                    ->getById($_session->getCustomerId());
            } else {
                return null;
            }
        }
        return $this->customer;
    }
}
