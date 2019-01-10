<?php
declare(strict_types=1);

namespace MagentoJapan\Kuromoji\Model\ElasticSearch\Adapter\Index;

use Magento\Elasticsearch\Model\Adapter\Index\Builder as DefaultBuilder;

/**
 * ElasticSearch adapter builder extension for Kuromoji support.
 */
class Builder extends DefaultBuilder
{
    /**
     * @inheritdoc
     */
    protected function getTokenizer()
    {
        $tokenizer = [
            'default_tokenizer' => [
                'type' => 'kuromoji_tokenizer',
            ],
        ];
        return $tokenizer;
    }
}
