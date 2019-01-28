<?php
declare(strict_types=1);

namespace MagentoJapan\Price\Model\Config;

use \Magento\Store\Model\ScopeInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * System configuration of currencies handling.
 */
class System
{
    /**
     * Describes what round method should be used for currencies
     */
    const CONFIG_ROUND_METHOD = 'tax/calculation/round';

    /**
     * Describes currency symbol position;
     */
    const CONFIG_SYMBOL_POSITION = 'price/symbol/position';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * System constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get rounding method value
     *
     * @return mixed
     */
    public function getRoundMethod()
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_ROUND_METHOD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get position of currency symbol.
     *
     * @return mixed
     */
    public function getSymbolPosition()
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_SYMBOL_POSITION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get list of currencies without subunits.
     *
     * @return array
     */
    public function getIntegerCurrencies()
    {
        return [
            'BIF',
            'MGA',
            'CLP',
            'DJF',
            'PYG',
            'RWF',
            'GNF',
            'JPY',
            'VND',
            'VUV',
            'XAF',
            'KMF',
            'XOF',
            'KRW',
            'HUF',
            'XPF',
            'TWD'
        ];
    }
}
