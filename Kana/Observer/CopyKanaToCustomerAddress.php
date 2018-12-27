<?php
declare(strict_types=1);

namespace MagentoJapan\Kana\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Api\AbstractExtensibleObject;
use \Magento\Framework\Api\AttributeValueFactory;

/**
 * Copy kana fields to Customer Address entity.
 */
class CopyKanaToCustomerAddress implements ObserverInterface
{
    /**
     * @var AttributeValueFactory
     */
    private $attributeValueFactory;

    /**
     * @param AttributeValueFactory $attributeValueFactory
     */
    public function __construct(AttributeValueFactory $attributeValueFactory)
    {
        $this->attributeValueFactory = $attributeValueFactory;
    }

    /**
     * Assign Kana to destination object.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getSource();
        $target = $observer->getEvent()->getTarget();

        $fKana = $this->attributeValueFactory->create();
        $fKana->setAttributeCode('firstnamekana')
            ->setValue($order->getFirstnamekana());

        $lKana = $this->attributeValueFactory->create();
        $lKana->setAttributeCode('lastnamekana')
            ->setValue($order->getLastnamekana());

        $key = AbstractExtensibleObject::CUSTOM_ATTRIBUTES_KEY;

        $target->setData(
            $key,
            [
                'firstnamekana' => $fKana,
                'lastnamekana' => $lKana
            ]
        );

        return $this;
    }
}
