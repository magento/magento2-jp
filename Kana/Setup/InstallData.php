<?php
namespace MagentoJapan\Kana\Setup;

use Magento\Directory\Helper\Data;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Setup\EavSetup;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Localize setup factory
     *
     * @var EavSetupFactory
     */
    private $localizeSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $localizeSetupFactory
     */
    public function __construct(EavSetupFactory $localizeSetupFactory)
    {
        $this->localizeSetupFactory = $localizeSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->localizeSetupFactory->create(['setup' => $setup]);

        $setup->startSetup();

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
            $customerSetup->addAttribute(
                Customer::ENTITY,
                $code,
                $options
            );

            $customerSetup->addAttribute(
                'customer_address',
                $code,
                $options
            );
        }

        $this->installCustomerForms($customerSetup, $attributes);
        $setup->endSetup();

    }


    /**
     * @param \Magento\Eav\Setup\EavSetup $eavSetup
     * @param array $attributes
     */
    public function installCustomerForms(EavSetup $eavSetup, array $attributes)
    {
        $customer = (int)$eavSetup->getEntityTypeId('customer');
        $customerAddress = (int)$eavSetup->getEntityTypeId('customer_address');
        /**
         * @var ModuleDataSetupInterface $setup
         */
        $setup = $eavSetup->getSetup();

        $attributeIds = [];
        $select = $setup->getConnection()->select()->from(
            ['ea' => $setup->getTable('eav_attribute')],
            ['entity_type_id', 'attribute_code', 'attribute_id']
        )->where(
            'ea.entity_type_id IN(?)',
            [$customer, $customerAddress]
        );
        foreach ($setup->getConnection()->fetchAll($select) as $row) {
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
            $setup->getConnection()
                ->insertMultiple($setup->getTable('customer_form_attribute'), $data);
        }
    }
}
