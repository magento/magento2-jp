<?php
namespace MagentoJapan\Kana\Model\Config;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class System
{
    /**
     *
     */
    const CONFIG_ELEMENT_ORDER = 'localize/sort/';
    /**
     *
     */
    const CONFIG_COUNTRY_SHOW = 'localize/address/hide_country';
    /**
     *
     */
    const CONFIG_REQUIRE_KANA = 'customer/address/require_kana';

    /**
     *
     */
    const CONFIG_USE_KANA = 'customer/address/use_kana';
    /**
     *
     */
    const CONFIG_FIELDS_ORDER = 'localize/address/change_fields_order';

    const CONFIG_CHECKOUT_SORT = 'localize/sort/change_fields_order';
    /**
     *
     */
    const CONFIG_LOCALE = 'general/locale/code';

    private $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->getConfigValue(self::CONFIG_LOCALE);
    }

    /**
     * @return mixed
     */
    public function getElementOrder()
    {
        return $this->getConfigValue(self::CONFIG_ELEMENT_ORDER);
    }

    /**
     * @return mixed
     */
    public function getShowCounry()
    {
        return $this->getConfigValue(self::CONFIG_COUNTRY_SHOW);
    }

    /**
     * @return mixed
     */
    public function getRequireKana()
    {
        return $this->getConfigValue(self::CONFIG_REQUIRE_KANA);
    }

    /**
     * @return mixed
     */
    public function getUseKana()
    {
        return $this->getConfigValue(self::CONFIG_USE_KANA);
    }

    /**
     * @return mixed
     */
    public function getChangeFieldsOrder()
    {
        return $this->getConfigValue(self::CONFIG_FIELDS_ORDER);
    }

    public function getSortOrder()
    {
        return $this->getConfigValue(self::CONFIG_CHECKOUT_SORT);
    }


    /**
     * @param $key
     * @return mixed
     */
    public function getConfigValue($key)
    {
        return $this->scopeConfig->getValue($key, ScopeInterface::SCOPE_STORE);
    }
}