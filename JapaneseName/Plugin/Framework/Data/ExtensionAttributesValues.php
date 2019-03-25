<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Plugin\Framework\Data;

use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\Data\Form;

/**
 * Behavior of this class should be moved to Magento\Framework\DataObject\Copy eventually.
 *
 * If form contains field and object contains corresponding extension attribute then it should be used as field value.
 */
class ExtensionAttributesValues
{
    /**
     * Add extension attribute values to form.
     *
     * Implementation is based on implementation details but not on interface of extensible attributes.
     * Avoid such approach if possible.
     *
     * @param Form $formIn
     * @param Form $formOut
     * @param mixed $values
     * @return Form
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSetValues(Form $formIn, Form $formOut, $values)
    {
        if (!isset($values['extension_attributes'])) {
            return $formOut;
        }

        $extensions = [];
        if ($values['extension_attributes'] instanceof AbstractSimpleObject) {
            $extensions = $values['extension_attributes']->__toArray();
        }

        if (empty($extensions)) {
            return $formOut;
        }

        $formOut->addValues($extensions);
        return $formOut;
    }
}
