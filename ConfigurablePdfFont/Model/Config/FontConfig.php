<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\ConfigurablePdfFont\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use CommunityEngineering\ConfigurablePdfFont\Model\Font;
use CommunityEngineering\ConfigurablePdfFont\Model\FontProvider;

/**
 * Configuration of fonts to use during PDF generation.
 *
 * Now configuration declared in di.xml file. If functionality will be moved to Magento Framework
 * new type olf XML configuration should be introduced.
 */
class FontConfig
{
    /**
     * Code of font to use as a default
     */
    const DEFAULT_FONT_CODE = 'default';

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @var FontProvider
     */
    private $fontProvider;

    /**
     * @param ScopeConfigInterface $config
     * @param FontProvider $fontProvider
     */
    public function __construct(
        ScopeConfigInterface $config,
        FontProvider $fontProvider
    ) {
        $this->config = $config;
        $this->fontProvider = $fontProvider;
    }

    /**
     * Get font selected by admin.
     *
     * @return Font
     */
    public function getActiveFont(): Font
    {
        $fontCode = $this->config->getValue('general/pdf/font');
        $font = $this->fontProvider->getFont($fontCode);
        return $font;
    }

    /**
     * Get default system font.
     *
     * @return Font
     */
    public function getDefaultFont(): Font
    {
        $font = $this->fontProvider->getFont(self::DEFAULT_FONT_CODE);
        return $font;
    }
}
