<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Configuration for country field displaying.
 */
class CountryInputConfig
{
    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @param ScopeConfigInterface $config
     */
    public function __construct(ScopeConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Determine if country field should be present at storefront address forms.
     *
     * @return bool
     */
    public function isVisibleAtStorefront(): bool
    {
        $configValue = $this->config->getValue('customer/address/country_show', ScopeInterface::SCOPE_STORE);
        return (bool)$configValue;
    }
}
