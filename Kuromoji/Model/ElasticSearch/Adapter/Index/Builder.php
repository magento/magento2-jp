<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace MagentoCommunity\Kuromoji\Model\ElasticSearch\Adapter\Index;

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
                'mode'=> 'search',
                'discard_punctuation'=> 'true',
                'user_dictionary' => 'search_dic.csv'
            ],
        ];
        return $tokenizer;
    }

    /**
     * @inheritdoc
     */
    public function build()
    {
        $tokenizer = $this->getTokenizer();
        $analyzerFilter = [
            'cjk_width',
            'kuromoji_part_of_speech',
            'kuromoji_baseform'
        ];
        $filter = $this->getFilter();
        $filter['synonym_dict'] = [
            'type' => 'synonym',
            'synonyms_path' => 'synonym.txt'
        ];
        $charFilter = $this->getCharFilter();

        $settings = [
            'analysis' => [
                'analyzer' => [
                    'default' => [
                        'type' => 'custom',
                        'tokenizer' => key($tokenizer),
                        'filter' => array_merge(
                            ['lowercase', 'keyword_repeat'],
                            array_keys($filter),
                            $analyzerFilter,
                            ['synonym_dict']
                        ),
                        'char_filter' => array_keys($charFilter)
                    ]
                ],
                'tokenizer' => $tokenizer,
                'filter' => $filter,
                'char_filter' => $charFilter,
            ],
        ];

        return $settings;
    }
}
