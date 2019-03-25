<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseYenFormatting\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Configuration of Japanese Yen formatting.
 */
class YenFormattingConfig
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Read configured Japanese Yen format.
     *
     * @return string
     */
    public function getFormat(): string
    {
        $configuredValue = $this->scopeConfig->getValue('currency/options/yen_formatting');
        return $configuredValue;
    }
}
