<?php
namespace MagentoJapan\Kana\Plugin\Quote\Model\Quote\Address;

use Magento\Quote\Model\Quote\Address\CustomAttributeList;

/**
 * Add Kana to attribute list.
 */
class AddKanaToAttributeList
{
    /**
     * Add Kana to attribute list.
     *
     * @param \Magento\Quote\Model\Quote\Address\CustomAttributeList $subject
     * @param array $attributes
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetAttributes(
        CustomAttributeList $subject,
        array $attributes
    ) {
        $attributes['firstnamekana'] = 'firstnamekana';
        $attributes['lastnamekana'] = 'lastnamekana';

        return $attributes;
    }
}
