<?php
namespace MagentoJapan\Pdf\Model\Config\Source;

/**
 * Class JapaneseFont
 * @package MagentoJapan\Pdf\Model\Config\Source
 */
class JapaneseFont implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'ipag.ttf', 'label' => __('IPA Gothic')],
            ['value' => 'ipagp.ttf', 'label' => __('IPA P Gothic')],
            ['value' => 'ipam.ttf', 'label' => __('IPA Mincho')],
            ['value' => 'ipamp.ttf', 'label' => __('IPA P Mincho')]
        ];
    }
}