<?php

namespace MagentoJapan\Kana\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        /**
         * update columns created_at and updated_at in sales entities tables
         */

        $tables = ['sales_order',
                    'quote',
                ];
        /** @var \Magento\Framework\DB\Adapter\AdapterInterface $connection */
        $connection = $installer->getConnection();
        foreach ($tables as $table) {
            $columns = $connection->describeTable($installer->getTable($table));
            if (!isset($columns['customer_lastnamekana'])) {
                $setup->getConnection()
                    ->addColumn(
                        $table,
                    'customer_lastnamekana',
                    [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' => 'Customer Lastname Kana']);
            }
            if (!isset($columns['customer_firstnamekana'])) {
                $setup->getConnection()
                    ->addColumn(
                        $table,
                    'customer_firstnamekana',
                    [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' => 'Customer Firstname Kana']);
            }
        }

        $tables = [
            'sales_order_address',
            'quote_address'
        ];
        foreach ($tables as $table) {
            $columns = $connection->describeTable($installer->getTable($table));
            if (!isset($columns['lastnamekana'])) {
                $setup->getConnection()
                    ->addColumn(
                        $table,
                        'lastnamekana',
                        [
                            'type' => Table::TYPE_TEXT,
                            'length' => 255,
                            'comment' => 'Customer Lastname Kana']);
            }
            if (!isset($columns['firstnamekana'])) {
                $setup->getConnection()
                    ->addColumn(
                        $table,
                        'firstnamekana',
                        [
                            'type' => Table::TYPE_TEXT,
                            'length' => 255,
                            'comment' => 'Customer Firstname Kana']);
            }
        }
    }
}
