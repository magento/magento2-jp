<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Options for kana usage.
 */
class Kana implements OptionSourceInterface
{
    const TYPE_KATAKANA = 1;
    const TYPE_HIRAGANA = 2;
    const TYPE_ANY = 3;

    /**
     * List possible kana input types
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            self::TYPE_KATAKANA => __('Only Katakana'),
            self::TYPE_HIRAGANA => __('Only Hiragana'),
            self::TYPE_ANY => __('Both')
        ];
    }
}
