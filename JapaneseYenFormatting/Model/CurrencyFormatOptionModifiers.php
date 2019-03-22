<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseYenFormatting\Model;

use CommunityEngineering\JapaneseYenFormatting\Model\Config\Source\YenFormatting;
use CommunityEngineering\JapaneseYenFormatting\Model\Config\YenFormattingConfig;

/**
 * Service to detect significant options for currency format.
 */
class CurrencyFormatOptionModifiers
{
    /**
     * @var YenFormattingConfig
     */
    private $yenFormattingConfig;

    /**
     * @param YenFormattingConfig $yenFormattingConfig
     */
    public function __construct(YenFormattingConfig $yenFormattingConfig)
    {
        $this->yenFormattingConfig = $yenFormattingConfig;
    }

    /**
     * Detect what options should be applied for correct currency displaying.
     *
     * @param string $currencyCode
     * @return array
     */
    public function getOptions(string $currencyCode): array
    {
        if ($currencyCode !== 'JPY') {
            return [];
        }

        switch ($this->yenFormattingConfig->getFormat()) {
            case YenFormatting::DEFAULT:
                return [];
            case YenFormatting::KANJI:
                return [
                    'position' => \Zend_Currency::RIGHT,
                    'symbol' => __('Yen'),
                ];
            default:
                throw new \InvalidArgumentException('Unsupported format');
        }
    }
}
