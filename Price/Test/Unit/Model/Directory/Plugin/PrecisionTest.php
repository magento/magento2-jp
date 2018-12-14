<?php
namespace MagentoJapan\Price\Test\Unit\Model\Directory\Plugin;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class PrecisionTest extends TestCase
{
    /**
     * Precision Plugin
     *
     * @var \MagentoJapan\Price\Model\Directory\Plugin\Precision
     */
    private $precisionPlugin;

    /**
     * Container for price text
     *
     * @var \Closure
     */
    private $closure;

    /**
     * @var \MagentoJapan\Price\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    private $helperMock;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->helperMock = $this->getMockBuilder(
            'MagentoJapan\Price\Helper\Data'
        )->disableOriginalConstructor()->getMock();

        $objectManager = new ObjectManager($this);
        $this->precisionPlugin = $objectManager->getObject(
            'MagentoJapan\Price\Model\Directory\Plugin\Precision',
            ['helper' => $this->helperMock]
        );

        $this->closure = function () {
            return '<span class="price">￥100</span>';
        };
    }

    /**
     * Test for aroundFormatPrecision to JPY
     *
     * @return void
     */
    public function testJpyAroundFormatPrecision()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Directory\Model\Currency $currency */
        $currency = $this->getMockBuilder('Magento\Directory\Model\Currency')
        ->disableOriginalConstructor()->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('JPY');
        $this->helperMock->expects($this->atLeastOnce())
            ->method('getIntegerCurrencies')->willReturn(['JPY']);


        $this->assertEquals(
            '<span class="price">￥100</span>',
            $this->precisionPlugin
                ->aroundFormatPrecision(
                    $currency,
                    $this->closure,
                    100.49,
                    [],
                    true,
                    false
                )
        );
    }

    /**
     * Test for aroundFormatPrecision to others
     *
     * @return void
     */
    public function testNonJpyAroundFormatPrecision()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Directory\Model\Currency $currency */
        $currency = $this->getMockBuilder('Magento\Directory\Model\Currency')
            ->disableOriginalConstructor()
            ->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('USD');
        $this->helperMock->expects($this->atLeastOnce())
            ->method('getIntegerCurrencies')->willReturn(['JPY']);

        $this->assertNotEquals(
            '<span class="price">￥100.49</span>',
            $this->precisionPlugin
                ->aroundFormatPrecision(
                    $currency,
                    $this->closure,
                    100.49,
                    [],
                    true,
                    false
                )
        );
    }
}