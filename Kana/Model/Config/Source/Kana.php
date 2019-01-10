<?php
declare(strict_types=1);

namespace MagentoJapan\Kana\Model\Config\Source;

/**
 * Kana configuration source.
 */
class Kana implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Retrieve possible customer address types.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            '1' => __('Only Katakana'),
            '2' => __('Only Hiragana'),
            '0' => __('Both')
        ];
    }
}
