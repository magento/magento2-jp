<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use CommunityEngineering\JapaneseAddress\Model\Config\CountryInputConfig;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Visibility of country field at checkout page should be controlled by admin.
 */
class CountryFieldVisibility implements LayoutProcessorInterface
{
    /**
    * @var ScopeConfigInterface
    */
    private $scopeConfig;

    /**
     * @var CountryInputConfig
     */
    private $countryFieldConfig;

    /**
     * @param \CommunityEngineering\JapaneseAddress\Model\Config\CountryInputConfig $countryFieldConfig
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        CountryInputConfig $countryFieldConfig,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->countryFieldConfig = $countryFieldConfig;
    }

    /**
     * @inheritdoc
     */
    public function process($jsLayout)
    {
        $locale = $this->getStoreLocale();
        if ($locale !== 'ja_JP') {
            return $jsLayout;
        }

        if (!isset($jsLayout['components']['checkout']['children']['steps'])) {
            return $jsLayout;
        }

        $jsLayout['components']['checkout']['children']['steps'] = $this->walkChildren(
            $jsLayout['components']['checkout']['children']['steps']
        );

        return $jsLayout;
    }

    /**
     * Walk though components and modify visibility of all country components.
     *
     * @param array $component
     * @return array
     */
    private function walkChildren(array $component): array
    {
        if (!isset($component['children']) || !is_array($component['children'])) {
            return $component;
        }

        foreach ($component['children'] as $name => $child) {
            if ($name === 'country_id') {
                $component['children'][$name]['visible'] = $this->countryFieldConfig->isVisibleAtStorefront();
            } else {
                $component['children'][$name] = $this->walkChildren($child);
            }
        }
        return $component;
    }

    /**
     * Retrieve current store locale from system configuration.
     *
     * @return mixed
     */
    private function getStoreLocale()
    {
        return $this->scopeConfig->getValue(DirectoryHelper::XML_PATH_DEFAULT_LOCALE, ScopeInterface::SCOPE_STORE);
    }
}
