<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Configuration of kana usage.
 */
class KanaFieldsConfig
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
     * Should kana fields be present in customer and address data?
     *
     * @return bool
     */
    public function areEnabled(): bool
    {
        $configValue = $this->config->getValue('customer/address/use_kana', ScopeInterface::SCOPE_STORE);
        return (bool)$configValue;
    }

    /**
     * Are kana fields required data?
     *
     * @return bool
     */
    public function areRequired(): bool
    {
        if (!$this->areEnabled()) {
            return false;
        }

        $configValue = $this->config->getValue('customer/address/require_kana', ScopeInterface::SCOPE_STORE);
        return (bool)$configValue;
    }

    /**
     * Specify what type of kana may be used.
     *
     * @return int
     */
    public function getKanaType(): int
    {
        $configValue = $this->config->getValue('customer/address/kana_type', ScopeInterface::SCOPE_STORE);
        if (!in_array($configValue, [
            Source\Kana::TYPE_HIRAGANA,
            Source\Kana::TYPE_KATAKANA,
            Source\Kana::TYPE_ANY,
        ])) {
            throw new \InvalidArgumentException(sprintf(
                'Ivalid kana type "%s" specified in config',
                $configValue
            ));
        }
        return (int)$configValue;
    }
}
