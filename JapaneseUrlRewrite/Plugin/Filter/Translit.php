<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseUrlRewrite\Plugin\Filter;

use CommunityEngineering\JapaneseUrlRewrite\Model\Transliterator;

/**
 * If Magento Core transliteration filter fails. Try to apply Katakana and Hiragana transliterators.
 *
 * Kanji transliteration is not implemented as it kan not be unambiguous transliteration.
 */
class Translit
{
    /**
     * @var Transliterator
     * ]
     */
    private $transliterator;

    /**
     * @param Transliterator $transliterator
     */
    public function __construct(Transliterator $transliterator)
    {
        $this->transliterator = $transliterator;
    }

    /**
     * Apply Japanese transliterator if original fails.
     *
     * @param \Magento\Framework\Filter\Translit $filter
     * @param string $result
     * @param string $input
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterFilter($filter, $result, $input)
    {
        if ($result === $input && !empty($input)) {
            $result = $this->transliterator->transliterate($input);
        }

        return $result;
    }
}
