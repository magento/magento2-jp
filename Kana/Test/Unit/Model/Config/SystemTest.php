<?php

namespace MagentoJapan\Kana\Test\Unit\Model\Config;

use \MagentoJapan\Kana\Model\Config\System;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\TestCase;

class SystemTest extends TestCase
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeMock;

    /**
     * @var \MagentoJapan\Kana\Model\Config\System|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $system;

    /**
     *
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->scopeMock =
            $this->getMockBuilder('Magento\Framework\App\Config\ScopeConfigInterface')
                ->disableOriginalConstructor()
                ->getMock();
        $this->system =
            $objectManager->getObject('\MagentoJapan\Kana\Model\Config\System',
                ['scopeConfig' => $this->scopeMock]);
    }

    /**
     * @param $path
     * @param $expected
     * @param $result
     *
     * @dataProvider getLocaleProvider
     */
    public function testGetLocale($path, $expected, $result)
    {
        $map = [
            [$path, ScopeInterface::SCOPE_STORE, null, $result]
        ];

        $this->scopeMock->expects(self::any())
            ->method('getValue')
            ->will($this->returnValueMap($map));

        $value = $this->system->getLocale();
        $this->assertEquals($expected, $value);
    }

    /**
     * @return array
     */
    public function getLocaleProvider()
    {
        return [
            ['general/locale/code', 'ja_JP', 'ja_JP'],
            ['general/locale/code', 'en_US', 'en_US'],
        ];
    }


    /**
     * @param $path
     * @param $expected
     * @param $result
     *
     * @dataProvider getElementOrderProvider
     */
    public function testGetElementOrder($path, $expected, $result)
    {
        $map = [
            [$path, ScopeInterface::SCOPE_STORE, null, $result]
        ];

        $this->scopeMock->expects(self::any())
            ->method('getValue')
            ->will($this->returnValueMap($map));

        $value = $this->system->getElementOrder();

        $this->assertEquals($expected, $value);
    }

    /**
     * @return array
     */
    public function getElementOrderProvider()
    {
        return [
            ['localize/sort/',
                [
                    'lastname' => '1',
                    'firstname' => '2',
                ],
                [
                    'lastname' => '1',
                    'firstname' => '2',
                ]
            ],
        ];
    }


    /**
     * @param $path
     * @param $expected
     * @param $result
     *
     * @dataProvider getShowCountryProvider
     */
    public function testGetShowCounry($path, $expected, $result)
    {
        $map = [
            [$path, ScopeInterface::SCOPE_STORE, null, $result]
        ];

        $this->scopeMock->expects(self::any())
            ->method('getValue')
            ->will($this->returnValueMap($map));

        $value = $this->system->getShowCounry();

        $this->assertEquals($expected, $value);
    }

    /**
     * @return array
     */
    public function getShowCountryProvider()
    {
        return [
            ['localize/address/hide_country', '1', '1'],
            ['localize/address/hide_country', '0', '0'],
        ];
    }


    /**
     * @param $path
     * @param $expected
     * @param $result
     *
     * @dataProvider getRequireKanaProvider
     */
    public function testGetRequireKana($path, $expected, $result)
    {
        $map = [
            [$path, ScopeInterface::SCOPE_STORE, null, $result]
        ];

        $this->scopeMock->expects(self::any())
            ->method('getValue')
            ->will($this->returnValueMap($map));

        $value = $this->system->getRequireKana();

        $this->assertEquals($expected, $value);
    }

    /**
     * @return array
     */
    public function getRequireKanaProvider()
    {
        return [
            ['customer/address/require_kana', '1', '1'],
            ['customer/address/require_kana', '0', '0'],
        ];
    }

    /**
     * @param $path
     * @param $expected
     * @param $result
     *
     * @dataProvider getUseKanaProvider
     */
    public function testGetUseKana($path, $expected, $result)
    {
        $map = [
            [$path, ScopeInterface::SCOPE_STORE, null, $result]
        ];

        $this->scopeMock->expects(self::any())
            ->method('getValue')
            ->will($this->returnValueMap($map));

        $value = $this->system->getUseKana();

        $this->assertEquals($expected, $value);
    }

    /**
     * @return array
     */
    public function getUseKanaProvider()
    {
        return [
            ['customer/address/use_kana', '1', '1'],
            ['customer/address/use_kana', '0', '0'],
        ];
    }


    /**
     * @param $path
     * @param $expected
     * @param $result
     *
     * @dataProvider getChangeFieldsOrderProvider
     */
    public function testGetChangeFieldsOrder($path, $expected, $result)
    {
        $map = [
            [$path, ScopeInterface::SCOPE_STORE, null, $result]
        ];

        $this->scopeMock->expects(self::any())
            ->method('getValue')
            ->will($this->returnValueMap($map));

        $value = $this->system->getChangeFieldsOrder();

        $this->assertEquals($expected, $value);
    }


    /**
     * @return array
     */
    public function getChangeFieldsOrderProvider()
    {
        return [
            ['localize/address/change_fields_order', '1', '1'],
            ['localize/address/change_fields_order', '0', '0'],
        ];
    }
}
