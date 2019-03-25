<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseRegion\Model\ResourceModel\Directory\Region;

use Magento\Framework\Data\Collection as DataCollection;

/**
 * Region sorting capability for ja_JP locale.
 *
 * Implementation based on fact that regions registered in correct order during registration.
 * Warning: May change order of non Japanese regions.
 */
class Collection extends \Magento\Directory\Model\ResourceModel\Region\Collection
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(
            \Magento\Directory\Model\Region::class,
            \Magento\Directory\Model\ResourceModel\Region::class
        );

        $this->_countryTable = $this->getTable('directory_country');
        $this->_regionNameTable = $this->getTable('directory_country_region_name');

        $this->addOrder('region_id', DataCollection::SORT_ORDER_ASC);
    }
}
