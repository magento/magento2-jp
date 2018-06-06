<?php
namespace MagentoJapan\Kana\Plugin\Checkout\Block\Checkout;

use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Model\Session;
use MagentoJapan\Kana\Helper\Data;

class LayoutProcessor
{
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
     * @var \MagentoJapan\Kana\Helper\Data
     */
    private $helper;


    /**
     * LayoutProcessor constructor.
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \MagentoJapan\Kana\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        Session $customerSession,
        CustomerRepository $customerRepository,
        Data $helper
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {
        $locale = $this->helper->getLocale();
        $format = $this->helper->getSortOrder();

        $hideCountry = $this->helper->getShowCounry();
        $useKana = $this->helper->getUseKana();
        $requireKana = $this->helper->getRequireKana();

        if ($format) {
            $shippingElements =& $jsLayout['components']['checkout']['children']
            ['steps']['children']['shipping-step']['children']['shippingAddress']
            ['children']['shipping-address-fieldset']['children'];

            foreach ($shippingElements as $key => &$shippingelement) {
                if ($key == 'region_id') {
                    $key = 'region';
                }
                $path = self::CONFIG_ELEMENT_ORDER . $key;
                $config = $this->helper->getConfigValue($path);
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
                if(!isset($method['dataScopePrefix'])) {
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
                    $config = $this->helper->getConfigValue($path);
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