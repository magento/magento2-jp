<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\JapaneseName\Plugin\Customer\Model\Metadata;

use Magento\Customer\Api\MetadataInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Register kana custom attributes.
 */
class KanaAttributesInjection
{
    /**
     * Add kana attributes metadata.
     *
     * @param MetadataInterface $metadata
     * @param array $attributes
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterGetCustomAttributesMetadata(MetadataInterface $metadata, array $attributes)
    {
        $attributes = $this->injectCustomAttributeByCode($metadata, $attributes, 'firstnamekana');
        $attributes = $this->injectCustomAttributeByCode($metadata, $attributes, 'lastnamekana');
        return $attributes;
    }

    /**
     * Insert attribute metadata.
     *
     * @param MetadataInterface $metadata
     * @param array $attributes
     * @param string $attributeCode
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function injectCustomAttributeByCode(
        MetadataInterface $metadata,
        array $attributes,
        string $attributeCode
    ) : array {
        if ($this->isAttributeInList($attributes, $attributeCode)) {
            return $attributes;
        }

        try {
            $attributeMetadata = $metadata->getAttributeMetadata($attributeCode);
            $attributes[] = $attributeMetadata;
        } catch (NoSuchEntityException $e) {
            // Ignore as 3-rd party extension may provide implementation without support of kana attributes
        }

        return $attributes;
    }

    /**
     * Check if kana attribute already present in metadata.
     *
     * @param array $attributes
     * @param string $attributeCode
     * @return bool
     */
    private function isAttributeInList(array $attributes, string $attributeCode) : bool
    {
        foreach ($attributes as $attribute) {
            if ($attribute->getAttributeCode() === $attributeCode) {
                return true;
            }
        }
        return false;
    }
}
