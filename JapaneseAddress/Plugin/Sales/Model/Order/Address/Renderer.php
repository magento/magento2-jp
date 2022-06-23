<?php

namespace CommunityEngineering\JapaneseAddress\Plugin\Sales\Model\Order\Address;

use CommunityEngineering\JapaneseAddress\Plugin\Customer\Model\Address\Config;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Directory\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Sales\Model\Order\Address;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Renderer
 *
 * @package CommunityEngineering\JapaneseAddress\Plugin\Sales\Model\Order\Address
 */
class Renderer
{
    const FORCE_BY_STORE_PREFIX = '_force_by_store';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface|null $scopeConfig
     */
    public function __construct(
        ?ScopeConfigInterface $scopeConfig = null
    ) {
        $this->scopeConfig = $scopeConfig ?: ObjectManager::getInstance()->get(ScopeConfigInterface::class);
    }

    /**
     * Resolve rendering order address by store locale
     *
     * @param \Magento\Sales\Model\Order\Address\Renderer $subject
     * @param \Magento\Sales\Model\Order\Address $address
     * @param string $type
     *
     * @return array
     */
    public function beforeFormat(\Magento\Sales\Model\Order\Address\Renderer $subject, Address $address, string $type)
    {
        $orderStore = $address->getOrder()->getStore();
        $localeCode = $this->getLocaleByStoreId((int) $orderStore->getId());

        if ($localeCode === 'ja_JP') {
            $type .= Config::JAPAN_LOCALE_SUFFIX;
        }

        return [$address, $type];
    }

    /**
     * Returns locale by storeId
     *
     * @param int $storeId
     * @return string
     */
    protected function getLocaleByStoreId(int $storeId): string
    {
        return $this->scopeConfig->getValue(Data::XML_PATH_DEFAULT_LOCALE, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
