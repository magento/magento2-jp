<?php
namespace MagentoJapan\Kuromoji\SearchAdapter\Query\Builder;

use Magento\Framework\Search\Request\BucketInterface;
use Magento\Framework\Search\RequestInterface;
use Magento\Elasticsearch\Model\Adapter\FieldMapperInterface;
use Magento\Elasticsearch\SearchAdapter\Query\Builder\Aggregation as BaseAggregation;
/**
 * @api
 * @since 100.1.0
 */
class Aggregation extends BaseAggregation
{
    /**
     * Build aggregation query for bucket
     *
     * @param array $searchQuery
     * @param BucketInterface $bucket
     * @return array
     * @since 100.1.0
     */
    protected function buildBucket(
        array $searchQuery,
        BucketInterface $bucket
    ) {
        $field = $this->fieldMapper->getFieldName($bucket->getField());
        switch ($bucket->getType()) {
            case BucketInterface::TYPE_TERM:
                $searchQuery['body']['aggregations'][$bucket->getName()]= [
                    'terms' => [
                        'field' => $field,
                        'size' => 2000
                    ],
                ];
                break;
            case BucketInterface::TYPE_DYNAMIC:
                $searchQuery['body']['aggregations'][$bucket->getName()]= [
                    'extended_stats' => [
                        'field' => $field,
                    ],
                ];
                break;
        }
        return $searchQuery;
    }
}
