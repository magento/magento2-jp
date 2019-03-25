<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseUrlRewrite\Model;

/**
 * Transliterator that try to convert Japanese text to Latin.
 *
 * Only Hiragana and Katakana may be handled. Transliteration of Kanji is not supported.
 * If string cannot be transliterated fully original string will be returned.
 */
class Transliterator
{
    /**
     * Transliterate Japanese text or return original string if not possible.
     *
     * @param string $original
     * @return string
     */
    public function transliterate(string $original): string
    {
        if ($this->containsOnlyAscii($original)) {
            return $original;
        }

        foreach ($this->getTransliterators() as $transliterator) {
            $transliterated = $transliterator->transliterate($original);
            if ($this->containsOnlyAscii($transliterated)) {
                return $transliterated;
            }
        }

        return $original;
    }

    /**
     * Creates list of ICU Transliterators to try.
     *
     * @return array
     *
     * @see http://userguide.icu-project.org/transforms/general
     */
    private function getTransliterators(): array
    {
        return [
            \Transliterator::create('Katakana-Latin'),
            \Transliterator::create('Hiragana-Latin'),
        ];
    }

    /**
     * Check if string contains only ASCII symbols.
     *
     * @param string $text
     * @return bool
     */
    private function containsOnlyAscii(string $text): bool
    {
        return (bool)mb_detect_encoding($text, 'ASCII', true);
    }
}
