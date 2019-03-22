<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Model\ResourceModel\Attribute as AttributeResource;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;

/**
 * Create Kana Custom attributes for customer and address.
 */
class KanaAttributes implements DataPatchInterface
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

        foreach ($this->getAffectedEntities() as $entityType => $entityAspect) {
            foreach ($this->getKanaAttributes() as $attributeCode => $attributeOptions) {
                if (isset($entityAspect['attributes'][$attributeCode])) {
                    $attributeOptions = array_merge($attributeOptions, $entityAspect['attributes'][$attributeCode]);
                }
                $customerSetup->addAttribute($entityType, $attributeCode, $attributeOptions);
                $attribute = $customerSetup->getEavConfig()->getAttribute($entityType, $attributeCode);
                $attribute->setData('used_in_forms', $entityAspect['used_in_forms']);
                $this->attributeResource->save($attribute);
            }
        }
    }

    /**
     * Read kana attributes config.
     *
     * Default behavior of some options may be overridden by values specified in admin configuration.
     *
     * @return array
     */
    private function getKanaAttributes(): array
    {
        $basicOptions = [
            'type' => 'static',
            'input' => 'text',
            'visible' => true,
            'required' => false,
            'validate_rules' => '{"max_text_length":255,"min_text_length":1}',
            'system' => true,
            'is_static' => true,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => true,
            'is_searchable_in_grid' => true,
        ];
        return [
            'firstnamekana' => array_merge(
                $basicOptions,
                [
                    'label' => 'First Name Kana',
                ]
            ),
            'lastnamekana' => array_merge(
                $basicOptions,
                [
                    'label' => 'Last Name Kana',
                ]
            )
        ];
    }

    /**
     * Get list of entites that contain kana attributes and forms where they should be present.
     *
     * @return array
     */
    private function getAffectedEntities(): array
    {
        return [
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER => [
                'used_in_forms' => [
                    'customer_account_create',
                    'customer_account_edit',
                    'adminhtml_customer',
                ],
                'attributes' => [
                    'firstnamekana' => [
                        'position' => 41,
                    ],
                    'lastnamekana' => [
                        'position' => 61,
                    ],
                ],
            ],
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS => [
                'used_in_forms' => [
                    'customer_register_address',
                    'customer_address_edit',
                    'adminhtml_customer_address',
                ],
                'attributes' => [
                    'firstnamekana' => [
                        'position' => 21,
                    ],
                    'lastnamekana' => [
                        'position' => 41,
                    ],
                ],
            ],
        ];
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
