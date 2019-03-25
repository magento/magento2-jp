<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use CommunityEngineering\JapaneseAddress\Model\Config\CountryInputConfig;

/**
 * Visibility of country field at checkout page should be controlled by admin.
 */
class CountryFieldVisibility implements LayoutProcessorInterface
{
    /**
     * @var CountryInputConfig
     */
    private $countryFieldConfig;

    /**
     * @param CountryInputConfig $countryFieldConfig
     */
    public function __construct(CountryInputConfig $countryFieldConfig)
    {
        $this->countryFieldConfig = $countryFieldConfig;
    }

    /**
     * @inheritdoc
     */
    public function process($jsLayout)
    {
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
}
