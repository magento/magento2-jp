<?php
namespace MagentoJapan\Kana\Model\Config;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class System
{
    /**
     * @var string
     */
    const CONFIG_ELEMENT_ORDER = 'localize/sort/';

    /**
     * @var string
     */
    const CONFIG_COUNTRY_SHOW = 'localize/address/hide_country';

    /**
     * @var string
     */
    const CONFIG_REQUIRE_KANA = 'customer/address/require_kana';

    /**
     * @var string
     */
    const CONFIG_USE_KANA = 'customer/address/use_kana';

    /**
     * @var string
     */
    const CONFIG_FIELDS_ORDER = 'localize/address/change_fields_order';

    /**
     * @var string
     */
    const CONFIG_CHECKOUT_SORT = 'localize/sort/change_fields_order';

    /**
     * @var string
     */
    const CONFIG_LOCALE = 'general/locale/code';

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
    public function getShowCountry()
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

    /**
     * @return mixed
     */
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
