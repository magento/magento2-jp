<?php
declare(strict_types=1);

namespace MagentoJapan\Region\Model\ResourceModel\Directory\Region;

use \Magento\Framework\Data\Collection as DataCollection;

/**
 * Region sorting capability for ja_JP locale.
 */
class Collection extends \Magento\Directory\Model\ResourceModel\Region\Collection
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(\Magento\Directory\Model\Region::class, \Magento\Directory\Model\ResourceModel\Region::class);

        $this->_countryTable = $this->getTable('directory_country');
        $this->_regionNameTable = $this->getTable('directory_country_region_name');

        $this->addOrder('region_id', DataCollection::SORT_ORDER_ASC);
    }
}
