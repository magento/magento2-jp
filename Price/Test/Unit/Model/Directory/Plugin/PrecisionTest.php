<?php
declare(strict_types=1);

namespace MagentoJapan\Price\Test\Unit\Model\Directory\Plugin;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class PrecisionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MagentoJapan\Price\Model\Directory\Plugin\Precision
     */
    private $precisionPlugin;

    /**
     * @var \Closure
     */
    private $closure;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $priceHelperMock = $this->getMockBuilder(\MagentoJapan\Price\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $priceHelperMock->expects($this->any())->method('getIntegerCurrencies')->willReturn(['JPY']);
        $priceHelperMock->expects($this->any())->method('getRoundMethod')->willReturn('round');

        $this->precisionPlugin = new \MagentoJapan\Price\Model\Directory\Plugin\Precision($priceHelperMock);

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
        $currency = $this->getMockBuilder(\Magento\Directory\Model\Currency::class)->disableOriginalConstructor()
            ->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('JPY');

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
        $currency = $this->getMockBuilder(\Magento\Directory\Model\Currency::class)
            ->disableOriginalConstructor()
            ->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('USD');

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
