<?php
namespace MagentoJapan\Kuromoji\Model\ElasticSearch\Adapter\Index;

use Magento\Elasticsearch\Model\Adapter\Index\Builder as DefaultBuilder;

/**
 * ElasticSeatch adapter builder extension for Kuromoji support.
 */
class Builder extends DefaultBuilder
{
    /**
     * @return array
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
