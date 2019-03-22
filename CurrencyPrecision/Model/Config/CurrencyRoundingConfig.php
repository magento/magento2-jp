<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Model\Config;

use \Magento\Store\Model\ScopeInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * System configuration of currencies rounding.
 */
class CurrencyRoundingConfig
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Return configured rounding mode for currencies.
     *
     * @return string
     */
    public function getRoundingMode(): string
    {
        $configuredVale = $this->scopeConfig->getValue(
            'currency/options/rounding_mode',
            ScopeInterface::SCOPE_WEBSITE
        );
        return $configuredVale;
    }
}
