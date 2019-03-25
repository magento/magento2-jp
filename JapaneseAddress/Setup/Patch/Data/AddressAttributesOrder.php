<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Model\ResourceModel\Attribute as AttributeResource;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;

/**
 * Create Kana Custom attributes for customer and address.
 */
class AddressAttributesOrder implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    private $setupFactory;

    /**
     * @var AttributeResource
     */
    private $attributeResource;

    /**
     * AddKana constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $setupFactory
     * @param AttributeResource $attributeResource
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $setupFactory,
        AttributeResource $attributeResource
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->setupFactory = $setupFactory;
        $this->attributeResource = $attributeResource;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->setupFactory->create(['resourceConnection' => $this->moduleDataSetup]);
        foreach ($this->getAffectedEntities() as $entityType) {
            $availableAttributes = $customerSetup->getEavConfig()->getEntityAttributes($entityType);
            $availableAttributes = $this->sortAttributesByPosition($availableAttributes);
            $orderedAttributes = array_flip($this->getAttributesOrder());
            $undefinedOrderShift = (count($orderedAttributes) + 1) * 10;

            foreach ($availableAttributes as $attributeCode => $attribute) {
                if (isset($orderedAttributes[$attributeCode])) {
                    $position = ($orderedAttributes[$attributeCode] + 1) * 10;
                } else {
                    $position = $undefinedOrderShift;
                    $undefinedOrderShift += 10;
                }

                $attribute->setData('position', $position);
                $attribute->setData('sort_order', $position);
                $this->attributeResource->save($attribute);
            }
        }
    }

    /**
     * Get ordered attribute codes.
     *
     * @return array
     */
    private function getAttributesOrder()
    {
        return [
            'prefix',
            'lastname',
            'middlename',
            'firstname',
            'suffix',
            'lastnamekana',
            'firstnamekana',
            'email',
            'country_id',
            'postcode',
            'region_id',
            'region',
            'city',
            'street',
            'telephone',
            'fax',
            'company',
        ];
    }

    /**
     * Get list of entities that contain kana attributes and forms where they should be present.
     *
     * @return array
     */
    private function getAffectedEntities(): array
    {
        return [
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
        ];
    }

    /**
     * Sort list of attributes by position.
     *
     * @param array $attributes
     * @return array
     */
    private function sortAttributesByPosition(array $attributes): array
    {
        uasort($attributes, function (AbstractAttribute $a1, AbstractAttribute $a2) {
            return $a1->getData('sort_order') - $a2->getData('sort_order');
        });
        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
