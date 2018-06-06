<?php
namespace MagentoJapan\Kana\Plugin\Quote\Model\Quote\Address;

use Magento\Quote\Model\Quote\Address\CustomAttributeList;

/**
 * Class AddKanaToAttributeList
 * @package MagentoJapan\Kana\Quote\Model\Quote\Address
 */
class AddKanaToAttributeList
{
    /**
     * @param \Magento\Quote\Model\Quote\Address\CustomAttributeList $subject
     * @param array $attributes
     * @return array
     */
    public function afterGetAttributes(
                                        CustomAttributeList $subject,
                                        array $attributes)
    {
        $attributes['firstnamekana'] = 'firstnamekana';
        $attributes['lastnamekana'] = 'lastnamekana';

        return $attributes;
    }
}