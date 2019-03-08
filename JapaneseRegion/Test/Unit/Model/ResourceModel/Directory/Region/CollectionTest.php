<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\JapaneseRegion\Test\Unit\Model\ResourceModel\Directory\Region;

use Magento\JapaneseRegion\Model\ResourceModel\Directory\Region\Collection;
use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\DataObject;
use Psr\Log\LoggerInterface;

/**
 * Test sort order of Regions for ja_JP locale.
 */
class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Region collection
     *
     * @var Collection
     */
    private $collection;

    /**
     * Locale resolver
     *
     * @var ResolverInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $localeResolverMock;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $entityFactoryMock = $this->getMockBuilder(EntityFactory::class)->disableOriginalConstructor()->getMock();
        $loggerMock = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();
        $fetchStrategyMock = $this->getMockBuilder(FetchStrategyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventManagerMock = $this->getMockBuilder(ManagerInterface::class)->disableOriginalConstructor()->getMock();
        $this->localeResolverMock = $this->getMockBuilder(ResolverInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $connectionMock = $this->getMockBuilder(Mysql::class)->disableOriginalConstructor()->getMock();
        $resourceMock = $this->getMockForAbstractClass(
            AbstractDb::class,
            [],
            '',
            false,
            true,
            true,
            ['getConnection', 'getMainTable', 'getTable', '__wakeup']
        );

        $selectMock = $this->getMockBuilder(Select::class)->disableOriginalConstructor()->getMock();
        $connectionMock->expects($this->any())
            ->method('select')
            ->will($this->returnValue($selectMock));
        $resourceMock->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($connectionMock));
        $resourceMock->expects($this->any())
            ->method('getTable')
            ->will($this->returnArgument(0));

        $this->collection = new Collection(
            $entityFactoryMock,
            $loggerMock,
            $fetchStrategyMock,
            $eventManagerMock,
            $this->localeResolverMock,
            $connectionMock,
            $resourceMock
        );
    }

    /**
     * Test for toOptionArray (Japanese)
     *
     * @return void
     * @throws \Exception
     */
    public function testToOptionArrayForJp()
    {
        $this->localeResolverMock
            ->expects($this->any())
            ->method('getLocale')
            ->willReturn('ja_JP');

        $items = [
            [
                'name' => 'Hokkaido',
                'default_name' => '北海道',
                'region_id' => 1,
                'country_id' => 1,
            ],
            [
                'name' => 'Aomori',
                'default_name' => '青森県',
                'region_id' => 2,
                'country_id' => 1,
            ],
        ];
        foreach ($items as $itemData) {
            $this->collection->addItem(new DataObject($itemData));
        }

        $expectedResult = [
            [
                'label' => __('Please select a region, state or province.'),
                'value' => null,
                'title' => null,
            ],
            [
                'value' => 1,
                'title' => '北海道',
                'country_id' => 1,
                'label' => 'Hokkaido',
            ],
            [
                'value' => 2,
                'title' => '青森県',
                'country_id' => 1,
                'label' => 'Aomori',
            ],
        ];

        $this->assertEquals($expectedResult, $this->collection->toOptionArray());
    }

    /**
     * Test for toOptionArray (non-Japanese)
     *
     * @return void
     * @throws \Exception
     */
    public function testToOptionArrayForEn()
    {
        $this->localeResolverMock
            ->expects($this->any())
            ->method('getLocale')
            ->willReturn('en_US');

        $items = [
            [
                'name' => 'Hokkaido',
                'default_name' => 'Hokkaido',
                'region_id' => 1,
                'country_id' => 1,
            ],
            [
                'name' => 'Aomori',
                'default_name' => 'Aomori',
                'region_id' => 2,
                'country_id' => 1,
            ],
        ];
        foreach ($items as $itemData) {
            $this->collection->addItem(new DataObject($itemData));
        }

        $expectedResult = [
            [
                'label' => __('Please select a region, state or province.'),
                'value' => null,
                'title' => null,
            ],
            [
                'value' => 1,
                'title' => 'Hokkaido',
                'country_id' => 1,
                'label' => 'Hokkaido',
            ],
            [
                'value' => 2,
                'title' => 'Aomori',
                'country_id' => 1,
                'label' => 'Aomori',
            ],
        ];

        $this->assertEquals($expectedResult, $this->collection->toOptionArray());
    }
}
