<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\ConfigurablePdfFont\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use CommunityEngineering\ConfigurablePdfFont\Model\FontProvider;

/**
 * List of available fonts.
 */
class Font implements OptionSourceInterface
{
    /**
     * @var FontProvider
     */
    private $fontProvider;

    /**
     * @param FontProvider $fontProvider
     */
    public function __construct(FontProvider $fontProvider)
    {
        $this->fontProvider = $fontProvider;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $fonts = [];
        foreach ($this->fontProvider->getFonts() as $fontCode => $font) {
            $fonts[$fontCode] = $font->getName();
        }
        return $fonts;
    }
}
