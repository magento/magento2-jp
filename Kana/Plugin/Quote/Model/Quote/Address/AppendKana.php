<?php
declare(strict_types=1);

namespace MagentoJapan\Kana\Plugin\Quote\Model\Quote\Address;

/**
 * Append Kana to attributes.
 *
 * @package MagentoJapan\Kana\Quote\Model\Quote\Address
 */
class AppendKana
{
    /**
     * Append Kana to attributes.
     *
     * @param \Magento\Quote\Model\Quote\Address $subject
     * @param mixed $attributes
     */
    public function beforeSetCustomAttributes(
        \Magento\Quote\Model\Quote\Address $subject,
        $attributes
    ) {
        foreach ($attributes as $code => $data) {
            if (in_array($code, ['firstnamekana', 'lastnamekana'])) {
                $subject->setData($code, $data->getValue());
            }
        }
    }
}
