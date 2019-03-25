<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Plugin\Customer\Block\Address\Edit;

use Magento\Customer\Block\Address\Edit;

/**
 * Use customer kana data for new addresses.
 */
class CopyKanaToNewAddress
{
    /**
     * Kopy values after layout prepeared.
     *
     * @param Edit $form
     * @return Edit
     */
    public function afterSetLayout(Edit $form)
    {
        if ($form->getAddress()->getId()) {
            return $form;
        }

        $this->copyAttributeFromCustomerToAddress($form, 'firstnamekana');
        $this->copyAttributeFromCustomerToAddress($form, 'lastnamekana');

        return $form;
    }

    /**
     * Copy customer custom attribute to corresponding address attribute.
     *
     * @param Edit $form
     * @param string $attributeCode
     */
    private function copyAttributeFromCustomerToAddress(Edit $form, string $attributeCode)
    {
        $form->getAddress()->setCustomAttribute(
            $attributeCode,
            $form->getCustomer()->getCustomAttribute($attributeCode)->getValue()
        );
    }
}
