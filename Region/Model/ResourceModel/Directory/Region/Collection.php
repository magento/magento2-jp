<?php
namespace MagentoJapan\Region\Model\ResourceModel\Directory\Region;

use \Magento\Framework\Data\Collection as DataCollection;

class Collection extends \Magento\Directory\Model\ResourceModel\Region\Collection
{
    /**
     * Define main, country, locale region name tables
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Magento\Directory\Model\Region',
            'Magento\Directory\Model\ResourceModel\Region'
        );

        $this->_countryTable = $this->getTable('directory_country');
        $this->_regionNameTable = $this->getTable('directory_country_region_name');

//        if ($this->_localeResolver->getLocale() != 'ja_JP') {
//            $this->addOrder('name', DataCollection::SORT_ORDER_ASC);
//            $this->addOrder('default_name', DataCollection::SORT_ORDER_ASC);
//        }
        $this->addOrder('region_id', DataCollection::SORT_ORDER_ASC);
    }

}

