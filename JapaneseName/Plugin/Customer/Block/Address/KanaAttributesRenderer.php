<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Plugin\Customer\Block\Address;

use Magento\Customer\Block\Address\Renderer\RendererInterface;

/**
 * Sales module uses customer address renderer but fields are stored as extension attributes.
 */
class KanaAttributesRenderer
{
    /**
     * Before pass data for rendering move extension attributes to top level.
     *
     * @param RendererInterface $renderer
     * @param array $addressAttributes
     * @param string $format
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeRenderArray(RendererInterface $renderer, array $addressAttributes, $format = null)
    {
        $addressAttributes = $this->flatAttribute($addressAttributes, 'firstnamekana');
        $addressAttributes = $this->flatAttribute($addressAttributes, 'lastnamekana');
        return [$addressAttributes, $format];
    }

    /**
     * Copy extension attribute to main data if missed.
     *
     * @param array $addressAttributes
     * @param string $attributeCode
     * @return array
     */
    private function flatAttribute(array $addressAttributes, string $attributeCode)
    {
        if (isset($addressAttributes[$attributeCode])) {
            return $addressAttributes;
        }

        if (!isset($addressAttributes['extension_attributes'])) {
            return $addressAttributes;
        }

        $extensions = $addressAttributes['extension_attributes'];
        $accessor = 'get' . join('', array_map('ucfirst', explode('_', $attributeCode)));
        if (!is_callable([$extensions, $accessor])) {
            return $addressAttributes;
        }

        $addressAttributes[$attributeCode] = $extensions->{$accessor}();

        return $addressAttributes;
    }
}
