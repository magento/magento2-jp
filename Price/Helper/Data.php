<?php
namespace MagentoJapan\Price\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package MagentoJapan\Price\Helper
 */
class Data extends AbstractHelper
{
    /**
     * Get rounding method value
     *
     * @return mixed
     */
    public function getRoundMethod()
    {
        return $this->scopeConfig->getValue(
            'tax/calculation/round',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getSymbolPosition()
    {
        return $this->scopeConfig->getValue(
            'price/symbol/position',
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
