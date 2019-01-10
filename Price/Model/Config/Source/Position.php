<?php
declare(strict_types=1);

namespace MagentoJapan\Price\Model\Config\Source;

/**
 * Position configuration.
 */
class Position implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Retrieve possible customer address types
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            16 => __('Right and use Kanji'),
            32 => __('Left and use symbol')
        ];
    }
}
