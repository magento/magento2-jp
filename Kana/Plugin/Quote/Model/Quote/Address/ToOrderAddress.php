<?php
namespace MagentoJapan\Kana\Plugin\Quote\Model\Quote\Address;

use Magento\Quote\Model\Quote\Address\ToOrderAddress as OrigToOrder;
use Magento\Quote\Model\Quote\Address;
use Magento\Sales\Api\Data\OrderAddressInterface;

class ToOrderAddress
{
    /**
     * @param \Magento\Quote\Model\Quote\Address\ToOrderAddress $toOrder
     * @param \Closure $proceed
     * @param \Magento\Quote\Model\Quote\Address $address
     * @param array $data
     * @return mixed
     */
    public function aroundConvert(OrigToOrder $toOrder,
                                  \Closure $proceed,
                                 Address $address,
                                 array $data = []
    )
    {
        $orderAddress = $proceed($address, $data);

        $orderAddress->setFirstnamekana($address->getFirstnamekana());
        $orderAddress->setLastnamekana($address->getLastnamekana());

        return $orderAddress;
    }
}