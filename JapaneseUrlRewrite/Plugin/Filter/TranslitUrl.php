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
 * Proper URL encoding/decoding is applied to allow national characters.
 */
class TranslitUrl
{
    /**
     * @var Transliterator
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
     * URL decode input before transliteration.
     *
     * Magento may pass same string several times for transliteration.
     * After plugin encode data to allow natinal characters ini URLs.
     *
     * @param \Magento\Framework\Filter\TranslitUrl $filter
     * @param string $input
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeFilter(
        \Magento\Framework\Filter\TranslitUrl $filter,
        $input
    ) {
        $decoded = urldecode($input);
        return [
            $decoded
        ];
    }

    /**
     * Apply Japanese transliterator if original fails.
     *
     * As now filtered text may contain not only URL safe characters it should be properly encoded.
     *
     * @param \Magento\Framework\Filter\TranslitUrl $filter
     * @param string $result
     * @param string $input
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterFilter(
        \Magento\Framework\Filter\TranslitUrl $filter,
        $result,
        $input
    ) {
        if (empty($result) && !empty($input)) {
            $result = $this->transliterator->transliterate($input);
        }

        $encoded = urlencode($result);
        return $encoded;
    }
}
