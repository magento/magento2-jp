<?php
namespace MagentoJapan\Price\Model\Config;

use \Magento\Store\Model\ScopeInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;

class System
{
    /**
     *
     */
    const CONFIG_ROUND_METHOD = 'tax/calculation/round';
    /**
     *
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