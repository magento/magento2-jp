<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseYenFormatting\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Variations of Japanese Yen formatting.
 */
class YenFormatting implements ArrayInterface
{
    /**
     * Default format (e.g. ￥123)
     */
    const DEFAULT = 'default';

    /**
     * Kanji from the right of amount (e.g. 123円)
     */
    const KANJI = 'kanji';

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            self::DEFAULT => __('Default (e.g. ￥123)'),
            self::KANJI => __('Kanji (e.g. 123円)'),
        ];
    }
}
