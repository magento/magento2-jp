# Kuromoji tokenizer adapter for ElasticSearch
By default, Magento can generate ElasticSearch default tokenizer based search index. This is useful for English and other non-agglutinative languages.
However, Japanese is one of the agglutinative languages. To analyze Japanese sentences correctly, morphological analysis engine is necessary.

This extension can improve Magento built-in ElasticSearch index. It uses kuromoji-neologd as tokenizer and analyzer. You need to install it before you start to using this extension.
Also, this extension can use synonym and user defined dictionary file. If you hope to improve your search result quality, please read ElasticSearch official documents and create your own dictionary and synonym files.

# Before install

Please install [Kuromoji Neologd](https://github.com/codelibs/elasticsearch-analysis-kuromoji-neologd) before you activate this extension.

Also create thse 2 files into ElasticSearch home directory.

- search_dic.csv
- synonym.txt

