<?php
namespace CommunityEngineering\CurrencyPrecision\Plugin\PayPal\Api;

use CommunityEngineering\CurrencyPrecision\Model\CurrencyRounding;
use \Magento\Paypal\Model\Api\Nvp;

class RoundAmount
{
    /**
     * @var CurrencyRounding
     */
    private $model;

    /**
     * RoundAmount constructor.
     * @param CurrencyRounding $model
     */
    public function __construct(
        CurrencyRounding $model
    ) {
        $this->model = $model;
    }

    /**
     * @param Nvp $nvp
     * @param \Closure $proceed
     * @param $price
     * @return string
     */
    public function aroundFormatPrice(
        Nvp $nvp,
        \Closure $proceed,
        $price
    ) {
        $currencyCode = $nvp->getCurrencyCode();
        $price = $this->model->round($currencyCode, $price);
        return sprintf('%.2F', $price);
    }
}
