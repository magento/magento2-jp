<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\Kuromoji\Model\ElasticSearch\Adapter\Index;

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
        $analyzerFilter = [
            'cjk_width',
            'kuromoji_part_of_speech',
            'kuromoji_baseform'
        ];

        $settings = parent::build();

        $settings['analysis']['analyzer']['default']['filter'] = array_merge(
            $settings['analysis']['analyzer']['default']['filter'], $analyzerFilter);

        return $settings;
    }
}
