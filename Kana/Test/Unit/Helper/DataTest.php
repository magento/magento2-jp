<?php
namespace MagentoJapan\Kana\Test\Unit\Helper;

use \MagentoJapan\Kana\Helper\Data;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\ScopeInterface;

class DataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeMock;

    /**
     * @var \MagentoJapan\Kana\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $helper;

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
        $contextMock = $this->getMockBuilder('Magento\Framework\App\Helper\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->scopeMock);
        $this->helper =
            $objectManager->getObject('MagentoJapan\Kana\Helper\Data',
                ['context'=>$contextMock]);
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

        $value = $this->helper->getLocale();

        $this->assertEquals($expected, $value);
    }

    /**
     * @return array
     */
    public function getLocaleProvider()
    {
        return [
            ['general/locale/code', 'ja_JP', 'ja_JP'],
            ['general/locale/code','en_US', 'en_US'],
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

        $value = $this->helper->getElementOrder();

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

        $value = $this->helper->getShowCounry();

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

        $value = $this->helper->getRequireKana();

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

        $value = $this->helper->getUseKana();

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

        $value = $this->helper->getChangeFieldsOrder();

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
