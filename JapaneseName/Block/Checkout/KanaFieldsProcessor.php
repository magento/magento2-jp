<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session;
use CommunityEngineering\JapaneseName\Model\Config\KanaFieldsConfig;
use CommunityEngineering\JapaneseName\Model\Config\Source\Kana;

/**
 * This processor configure kana input fields at one page checkout page.
 *
 * Initially input fields added by common Magento mechanism based on custom attributes declaration for customer address.
 *
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * Suppressed because of a bug in the \Magento\CodeMessDetector\Rule\Design\CookieAndSessionMisuse.
 * Session usage is eligible here.
 */
class KanaFieldsProcessor implements LayoutProcessorInterface
{
    /**
     * @var KanaFieldsConfig
     */
    private $kanaFieldsConfig;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @param KanaFieldsConfig $kanaFieldsConfig
     * @param Session $customerSession
     */
    public function __construct(
        KanaFieldsConfig $kanaFieldsConfig,
        Session $customerSession
    ) {
        $this->kanaFieldsConfig = $kanaFieldsConfig;
        $this->customerSession = $customerSession;
    }

    /**
     * @inheritdoc
     */
    public function process($jsLayout)
    {
        if (!isset($jsLayout['components']['checkout']['children']['steps'])) {
            return $jsLayout;
        }

        $jsLayout['components']['checkout']['children']['steps'] = $this->processChildrenKanaFields(
            $jsLayout['components']['checkout']['children']['steps']
        );

        return $jsLayout;
    }

    /**
     * Recursively iterate through components tree to find kana fields.
     *
     * By default kana fields present in shipping and billing address forms.
     *
     * @param array $component
     * @return array
     */
    private function processChildrenKanaFields(array $component): array
    {
        if (!isset($component['children']) || !is_array($component['children'])) {
            return $component;
        }

        foreach ($component['children'] as $name => $child) {
            if (in_array($name, ['firstnamekana', 'lastnamekana'], true)) {
                $component['children'][$name] = $this->processKanaField($name, $child);
            } else {
                $component['children'][$name] = $this->processChildrenKanaFields($child);
            }
        }
        return $component;
    }

    /**
     * Configure Kana fields.
     *
     * Modify config of component created from custom attribute declaration according to
     * setting provided by admin.
     *
     * @param string $name
     * @param array $config
     * @return array
     */
    private function processKanaField(string $name, array $config): array
    {
        $config['visible'] = $this->isKanaFieldsVisible();

        if ($this->isKanaFieldsRequired()) {
            $config['validation']['required-entry'] = true;
        } else {
            unset($config['validation']['required-entry']);
        }

        switch ($this->kanaFieldsConfig->getKanaType()) {
            case Kana::TYPE_HIRAGANA:
                $config['validation']['validate-hiragana'] = true;
                break;
            case Kana::TYPE_KATAKANA:
                $config['validation']['validate-katakana'] = true;
                break;
            default:
                $config['validation']['validate-kana'] = true;
                break;
        }

        $config['value'] = $this->getKanaFieldValue($name);
        if (!$config['value']) {
            unset($config['value']);
        }

        $config['dataScope'] = str_replace(
            '.' . $name,
            '.custom_attributes.' . $name,
            $config['dataScope']
        );

        return $config;
    }

    /**
     * Should kana fields be displayed to customer?
     *
     * @return bool
     */
    private function isKanaFieldsVisible(): bool
    {
        return $this->kanaFieldsConfig->areEnabled();
    }

    /**
     * Are kana fields required to place order?
     *
     * @return bool
     */
    private function isKanaFieldsRequired(): bool
    {
        return $this->kanaFieldsConfig->areRequired();
    }

    /**
     * Read kana field value for current customer.
     *
     * @param string $kanaField
     * @return null|string
     */
    private function getKanaFieldValue(string $kanaField):? string
    {
        $customer = $this->getCurrentCustomer();
        if ($customer === null) {
            return null;
        }
        $kanaFieldValue = $customer->getCustomAttribute($kanaField)->getValue();
        return $kanaFieldValue;
    }

    /**
     * Fetch current customer from session.
     *
     * @return CustomerInterface|null
     */
    private function getCurrentCustomer():? CustomerInterface
    {
        return $this->customerSession->getCustomerData();
    }
}
