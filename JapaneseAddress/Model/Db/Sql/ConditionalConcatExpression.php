<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Model\Db\Sql;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Sql\Expression;

/**
 * Expression to concatenate several parts. Parts are included only if condition satisfied.
 *
 * This class might be moved to framework if would be useful.
 */
class ConditionalConcatExpression extends Expression
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var string[]
     */
    private $parts;

    /**
     * @param ResourceConnection $resource
     * @param array $parts
     */
    public function __construct(
        ResourceConnection $resource,
        array $parts
    ) {
        $this->adapter = $resource->getConnection();
        $this->parts = $parts;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        $parts = [];

        foreach ($this->parts as $part) {
            $partValueSql = $this->getPartValueSql($part);
            $partConditionSql = $this->getPartConditionSql($part);

            $parts[] = $partConditionSql
                     ? $this->adapter->getCheckSql($partConditionSql, $partValueSql, "''")
                     : $partValueSql;
        }

        $sql = sprintf(
            'TRIM(%s)',
            $this->adapter->getConcatSql($parts)
        );
        return $sql;
    }

    /**
     * Get SQL for part value.
     *
     * @param array $part
     * @return string|\Zend_Db_Expr
     */
    private function getPartValueSql(array $part)
    {
        if (isset($part['static'])) {
            $part['static'] = str_replace('{space}', ' ', $part['static']);
            return new \Zend_Db_Expr(sprintf('"%s"', $part['static']));
        }
        if (isset($part['columnName'])) {
            return $this->adapter->quoteIdentifier($part['columnName']);
        }
        throw new \InvalidArgumentException('Invalid configuration provided.');
    }

    /**
     * Get SQL for condition to include part.
     *
     * @param array $part
     * @return null|\Zend_Db_Expr
     */
    private function getPartConditionSql(array $part)
    {
        if (isset($part['ifExpr'])) {
            return new \Zend_Db_Expr($part['ifExpr']);
        }
        if (isset($part['ifColumnName'])) {
            return new \Zend_Db_Expr($this->adapter->quoteIdentifier($part['ifColumnName']) . ' != ""');
        }
        if (isset($part['columnName'])) {
            return new \Zend_Db_Expr($this->adapter->quoteIdentifier($part['columnName']) . ' != ""');
        }
        return null;
    }
}
