<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Model\DataObject;

use Magento\Framework\Api\ExtensibleDataInterface;
/**
 * Behavior of this class should be moved to Magento\Framework\DataObject\Copy eventually.
 *
 * This class adds functionality of copying extension attributes through fieldsets mechanism.
 * @see https://devdocs.magento.com/guides/v2.3/ext-best-practices/tutorials/copy-fieldsets.html
 *
 * This class does not follow Magento recommended practices and suposed to be only temporal solution.
 * Please avoid such code.
 */
class Copy extends \Magento\Framework\DataObject\Copy
{
    /**
     * @inheritdoc
     */
    public function getDataFromFieldset($fieldset, $aspect, $source, $root = 'global')
    {
        $data = parent::getDataFromFieldset($fieldset, $aspect, $source, $root);
        if (!$source instanceof \Magento\Framework\Api\ExtensibleDataInterface) {
            return $data;
        }

        $extensionAttributes = $source->getExtensionAttributes();
        if ($extensionAttributes == null) {
            return $data;
        }

        $fields = $this->fieldsetConfig->getFieldset($fieldset, $root);
        if ($fields === null) {
            return $data;
        }

        foreach ($fields as $code => $node) {
            if (empty($node[$aspect])) {
                continue;
            }

            $method = 'get' . str_replace(
                ' ',
                '',
                ucwords(str_replace('_', ' ', $code))
            );
            if (!method_exists($extensionAttributes, $method)) {
                continue;
            }

            $value = $extensionAttributes->{$method}();

            $targetCode = (string)$node[$aspect];
            $targetCode = $targetCode == '*' ? $code : $targetCode;
            $data['extension_attributes'][$targetCode] = $value;
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    protected function _getFieldsetFieldValue($source, $code)
    {
        if (is_array($source)) {
            $value = isset($source[$code]) ? $source[$code] : null;
        } elseif ($source instanceof \Magento\Framework\Api\ExtensibleDataInterface) {
            $value = $this->getAttributeValueFromExtensibleDataObject($source, $code);
        } elseif ($source instanceof \Magento\Framework\DataObject) {
            $value = $source->getDataUsingMethod($code);
        } elseif ($source instanceof \Magento\Framework\Api\AbstractSimpleObject) {
            $sourceArray = $source->__toArray();
            $value = isset($sourceArray[$code]) ? $sourceArray[$code] : null;
        } else {
            throw new \InvalidArgumentException(
                'Source should be array, Magento Object, ExtensibleDataInterface, or AbstractSimpleObject'
            );
        }
        return $value;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributeValueFromExtensibleDataObject($source, $code)
    {
        try {
            return parent::getAttributeValueFromExtensibleDataObject($source, $code);
        } catch (\InvalidArgumentException $e) {
            if ($source instanceof \Magento\Framework\DataObject) {
                // use magic method fo consistency
                return $source->getDataUsingMethod($code);
            }
            throw $e;
        }
    }

    /**
     * @inheritdoc
     */
    protected function _setFieldsetFieldValue($target, $targetCode, $value)
    {
        $targetIsArray = is_array($target);

        if ($targetIsArray) {
            $target[$targetCode] = $value;
        } elseif ($target instanceof \Magento\Framework\Api\ExtensibleDataInterface) {
            // extensible objects must be handled before data object
            // as most of models are extensible and data objects at same time
            $this->setAttributeValueFromExtensibleDataObject($target, $targetCode, $value);
        } elseif ($target instanceof \Magento\Framework\DataObject) {
            $target->setDataUsingMethod($targetCode, $value);
        } elseif ($target instanceof \Magento\Framework\Api\AbstractSimpleObject) {
            $target->setData($targetCode, $value);
        } else {
            throw new \InvalidArgumentException(
                'Source should be array, Magento Object, ExtensibleDataInterface, or AbstractSimpleObject'
            );
        }

        return $target;
    }

    /**
     * @inheritdoc
     */
    protected function setAttributeValueFromExtensibleDataObject(ExtensibleDataInterface $target, $code, $value)
    {
        try {
            parent::setAttributeValueFromExtensibleDataObject($target, $code, $value);
        } catch (\InvalidArgumentException $e) {
            if ($target instanceof \Magento\Framework\DataObject) {
                // use magic method fo consistency
                $target->setData($code, $value);
                return;
            }
            throw $e;
        }

        return $target;
    }
}
