<?php
namespace MagentoJapan\Kana\Setup\Patch\Data;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;

/**
 * Class AddKana
 * @package MagentoJapan\Kana\Setup\Patch\Data
 */
class AddKana implements DataPatchInterface, PatchVersionInterface
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
     * AddKana constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $setupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $setupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->setupFactory = $setupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $setup = $this->moduleDataSetup;
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->setupFactory->create(['resourceConnection' => $setup]);

        $attributes = [
            'firstnamekana' =>
                [
                    'type' => 'varchar',
                    'input' => 'text',
                    'visible' => true,
                    'required' => false,
                    'system' => 0,
                    'sort_order' => 45,
                    'validate_rules' => '{"max_text_length":255,"min_text_length":1}',
                    'position' => 45,
                    'label' => 'First name kana',
                ],
            'lastnamekana' =>
                [
                    'type' => 'varchar',
                    'input' => 'text',
                    'visible' => true,
                    'required' => false,
                    'system' => 0,
                    'sort_order' => 65,
                    'validate_rules' => '{"max_text_length":255,"min_text_length":1}',
                    'position' => 65,
                    'label' => 'Last name kana',
                ]
        ];

        foreach ($attributes as $code => $options) {
            $customerAttribute = $customerSetup->getAttribute(
                CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER, $code
            );
            //if(!$customerAttribute->getId()) {
                $customerSetup->addAttribute(
                    CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                    $code,
                    $options
                );
            //}

            $addressAttribute = $customerSetup->getAttribute(
                AddressMetadataInterface::ENTITY_TYPE_ADDRESS, $code
            );
            //if(!$addressAttribute->getId()) {
                $customerSetup->addAttribute(
                    AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
                    $code,
                    $options
                );
            //}
        }

        $this->installCustomerForms($customerSetup, $attributes);

    }

    /**
     * @param CustomerSetup $eavSetup
     * @param array $attributes
     */
    public function installCustomerForms(EavSetup $eavSetup, array $attributes)
    {
        $customer = (int)$eavSetup->getEntityTypeId('customer');
        $customerAddress = (int)$eavSetup->getEntityTypeId('customer_address');

        /** @var AdapterInterface $connection */
        $connection = $this->moduleDataSetup->getConnection();

        $attributeIds = [];
        $select = $connection->select()->from(
            ['ea' => $this->moduleDataSetup->getTable('eav_attribute')],
            ['entity_type_id', 'attribute_code', 'attribute_id']
        )->where(
            'ea.entity_type_id IN(?)',
            [$customer, $customerAddress]
        );
        foreach ($connection->fetchAll($select) as $row) {
            if(preg_match('/kana/', $row['attribute_code'])) {
                $attributeIds[$row['entity_type_id']][$row['attribute_code']] = $row['attribute_id'];
            }
        }

        $data = [];
        $usedInCustomerForms = ['customer_account_create', 'customer_account_edit', 'adminhtml_customer'];
        $usedInAddressForms = ['customer_register_address','customer_address_edit', 'adminhtml_customer_address'];
        $usedInForms = [];

        foreach ($attributeIds as $entity => $attrs) {
            foreach ($attrs as $attributeCode => $attributeId) {
                if($entity == $customer) {
                    $usedInForms = $usedInCustomerForms;
                } else {
                    $usedInForms = $usedInAddressForms;
                }

                foreach ($usedInForms as $formCode) {
                    $data[] = ['form_code' => $formCode, 'attribute_id' => $attributeId];
                }
            }

        }

        if ($data) {
            $connection->insertMultiple(
                $this->moduleDataSetup->getTable('customer_form_attribute'), $data
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.0';
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
