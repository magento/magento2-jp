<?php
namespace MagentoJapan\Price\Test\Unit\Model\Directory\Plugin;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class PrecisionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Precision Plugin
     *
     * @var \MagentoJapan\Price\Model\Directory\Plugin\Precision
     */
    protected $precisionPlugin;

    /**
     * Container for price text
     *
     * @var \Closure
     */
    protected $closure;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->precisionPlugin = $objectManager->getObject(
            'MagentoJapan\Price\Model\Directory\Plugin\Precision'
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
        $currency = $this->getMock(
            'Magento\Directory\Model\Currency',
            [],
            [],
            '',
            false
        );
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
        $currency = $this->getMockBuilder('Magento\Directory\Model\Currency')
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