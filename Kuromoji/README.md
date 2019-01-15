# Kuromoji tokenizer adapter for ElasticSearch
By default, Magento can generate ElasticSearch default tokenizer based search index. This is useful for English and other non-agglutinative languages.
However, Japanese is one of the agglutinative languages. To analyze Japanese sentences correctly, morphological analysis engine is necessary.

This extension can improve Magento built-in ElasticSearch index. It uses Kuromoji as tokenizer and analyzer. You need to install it before you start to using this extension.
Also, this extension can use synonym and user defined dictionary file. If you hope to improve your search result quality, please read ElasticSearch official documents and create your own dictionary and synonym files.

# Before install

Please install [Kuromoji](https://www.elastic.co/guide/en/elasticsearch/plugins/master/analysis-kuromoji.html) before you activate this extension.

Also create these 2 files into ElasticSearch home directory.

- search_dic.csv
- synonym.txt

search_dic.csv is user defined dictionary. Its format is described in [ElasticSearch document](https://www.elastic.co/guide/en/elasticsearch/plugins/5.0/analysis-kuromoji-tokenizer.html).
synonym.txt is user defined synonym file. Its format is described in [ElasticSearch document](https://www.elastic.co/guide/en/elasticsearch/reference/5.0/analysis-synonym-tokenfilter.html).
