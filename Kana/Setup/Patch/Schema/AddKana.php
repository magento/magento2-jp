<?php
declare(strict_types=1);

namespace MagentoJapan\Kana\Setup\Patch\Schema;

use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Add Kana fields to DB schema.
 */
class AddKana implements SchemaPatchInterface
{
    /**
     * @var SchemaSetupInterface
     */
    private $setup;

    /**
     * @param SchemaSetupInterface $setup
     */
    public function __construct(
        SchemaSetupInterface $setup
    ) {
        $this->setup = $setup;
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $installer = $this->setup;

        /**
         * update columns created_at and updated_at in sales entities tables
         */

        $tables = [
            'sales_order',
            'quote',
        ];
        /** @var \Magento\Framework\DB\Adapter\AdapterInterface $connection */
        $connection = $installer->getConnection();
        foreach ($tables as $table) {
            $columns = $connection->describeTable($installer->getTable($table));
            if (!isset($columns['customer_lastnamekana'])) {
                $connection->addColumn(
                    $table,
                    'customer_lastnamekana',
                    [
                        'type' => Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Customer Lastname Kana'
                    ]
                );
            }
            if (!isset($columns['customer_firstnamekana'])) {
                $connection->addColumn(
                    $table,
                    'customer_firstnamekana',
                    [
                        'type' => Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Customer Firstname Kana'
                    ]
                );
            }
        }

        $tables = [
            'sales_order_address',
            'quote_address'
        ];
        foreach ($tables as $table) {
            $columns = $connection->describeTable($installer->getTable($table));
            if (!isset($columns['lastnamekana'])) {
                $connection->addColumn(
                    $table,
                    'lastnamekana',
                    [
                        'type' => Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Customer Lastname Kana'
                    ]
                );
            }
            if (!isset($columns['firstnamekana'])) {
                $connection->addColumn(
                    $table,
                    'firstnamekana',
                    [
                        'type' => Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Customer Firstname Kana'
                    ]
                );
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }
}
