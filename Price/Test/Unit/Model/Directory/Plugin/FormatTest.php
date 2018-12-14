<?php
namespace MagentoJapan\Price\Test\Unit\Model\Directory\Plugin;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class FormatTest extends TestCase
{
    /**
     * Price Format Plugin
     *
     * @var \MagentoJapan\Price\Model\Directory\Plugin\Format
     */
    private $formatPlugin;

    /**
     * Price Currency
     *
     * @var \Magento\Directory\Model\PriceCurrency|\PHPUnit_Framework_MockObject_MockObject
     */
    private $priceCurrency;

    /**
     * Price container closure
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
        $this->formatPlugin = $objectManager->getObject(
            'MagentoJapan\Price\Model\Directory\Plugin\Format',
            ['helper' => $this->helperMock]
        );

        $this->priceCurrency = $this->getMockBuilder(
            'Magento\Directory\Model\PriceCurrency'
        )->disableOriginalConstructor()->getMock();

        $this->closure = function () {
            return '<span class="price">￥100.49</span>';
        };
    }

    /**
     * Test for aroundFormat to JPY
     *
     * @return void
     */
    public function testJpyAroundFormat()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Directory\Model\Currency  $currency */
        $currency = $this->getMockBuilder('Magento\Directory\Model\Currency')
            ->disableOriginalConstructor()->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('JPY');
        $currency->expects($this->atLeastOnce())
            ->method('formatPrecision')
            ->willReturn('<span class="price">￥100</span>');
        $this->helperMock->expects($this->atLeastOnce())
            ->method('getIntegerCurrencies')->willReturn(['JPY']);
        $this->helperMock->expects($this->atLeastOnce())
            ->method('getRoundMethod')
            ->willReturn('round');


        $this->priceCurrency->expects($this->atLeastOnce())
            ->method('getCurrency')->willReturn($currency);

        $this->assertEquals(
            '<span class="price">￥100</span>',
            $this->formatPlugin->aroundFormat(
                $this->priceCurrency,
                $this->closure,
                100.49,
                2,
                null,
                null
            )
        );
    }

    /**
     * Test for aroundFormat to others
     *
     * @return void
     */
    public function testNonJpyAroundFormat()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Directory\Model\Currency  $currency */
        $currency = $this->getMockBuilder('Magento\Directory\Model\Currency')
            ->disableOriginalConstructor()
            ->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('USD');

        $this->priceCurrency->expects($this->atLeastOnce())
            ->method('getCurrency')->willReturn($currency);

        $this->helperMock->expects($this->atLeastOnce())
            ->method('getIntegerCurrencies')->willReturn(['JPY']);

        $this->assertNotEquals(
            '<span class="price">￥100</span>',
            $this->formatPlugin->aroundFormat(
                $this->priceCurrency,
                $this->closure,
                100.49,
                2,
                null,
                null
            )
        );
    }
}