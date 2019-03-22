<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseStoreAddress\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Configuration for store address information.
 */
class StoreAddressConfig
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
     * Get store address template for format.
     *
     * Supported formats are: text, oneline, html, pdf.
     *
     * @param string $format
     * @return string
     */
    public function getAddressTemplate(string $format): string
    {
        $configValue = $this->config->getValue('general/store_address_template/' . $format);
        return $configValue;
    }
}
