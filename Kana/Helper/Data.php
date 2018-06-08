<?php
namespace MagentoJapan\Kana\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package MagentoJapan\Kana\Helper
 */
class Data extends AbstractHelper
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
