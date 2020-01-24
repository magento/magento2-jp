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
 * Address usage configuration for customer registration.
 */
class CustomerRegistrationConfig
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
     * Determine if address data should be requested during customer registration.
     *
     * @return bool
     */
    public function isAddressRequired(): bool
    {
        $configValue = $this->config->getValue('customer/create_account/request_address', ScopeInterface::SCOPE_STORE);
        return (bool)$configValue;
    }
}
