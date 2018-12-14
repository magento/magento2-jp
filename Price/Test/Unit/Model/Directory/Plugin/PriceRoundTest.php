<?php

namespace MagentoJapan\Price\Test\Unit\Model\Directory\Plugin;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use \Magento\Directory\Model\PriceCurrency;
use \MagentoJapan\Price\Model\Config\System;
use \MagentoJapan\Price\Model\Directory\Plugin\PriceRound;
use PHPUnit\Framework\TestCase;

class PriceRoundTest extends TestCase
{
    /**
     * Price Round Plugin
     *
     * @var PriceRound
     */
    private $priceRoundPlugin;

    /**
     * Price Currency
     *
     * @var PriceCurrency|\PHPUnit_Framework_MockObject_MockObject
     */
    private $priceCurrency;

    /**
     * Helper
     *
     * @var System|\PHPUnit_Framework_MockObject_MockObject
     */
    private $system;

    /**
     * Closure for enclose price
     *
     * @var \Closure
     */
    private $closure;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->system = $this->getMockBuilder('MagentoJapan\Price\Model\Config\System')
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceRoundPlugin = $objectManager->getObject(
            'MagentoJapan\Price\Model\Directory\Plugin\PriceRound',
            ['system' => $this->system]
        );

        $this->priceCurrency = $this->getMockBuilder(
            'Magento\Directory\Model\PriceCurrency'
        )->disableOriginalConstructor()->getMock();

        $this->closure = function () {
            return 100;
        };
    }

    /**
     * Test for aroundConverterRound (round)
     *
     * @return void
     */
    public function testAroundConvertRoundRound()
    {
        $this->system->expects($this->atLeastOnce())
            ->method('getRoundMethod')
            ->willReturn('round');
        $this->system->expects($this->atLeastOnce())
            ->method('getIntegerCurrencies')->willReturn(['JPY']);
        $currency = $this->getMockBuilder('Magento\Directory\Model\Currency')
            ->disableOriginalConstructor()
            ->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('JPY');


        $this->priceCurrency->expects($this->atLeastOnce())
            ->method('getCurrency')->willReturn($currency);

        $this->assertEquals(
            100,
            $this->priceRoundPlugin->aroundConvertAndRound(
                $this->priceCurrency,
                $this->closure,
                100.49,
                [],
                null,
                'JPY',
                2
            )
        );
    }

    /**
     * Test for aroundConverterRound (ceil)
     *
     * @return void
     */
    public function testAroundConvertRoundCeil()
    {
        $this->system->expects($this->atLeastOnce())
            ->method('getRoundMethod')
            ->willReturn('ceil');
        $this->system->expects($this->atLeastOnce())
            ->method('getIntegerCurrencies')->willReturn(['JPY']);
        $currency = $this->getMockBuilder('Magento\Directory\Model\Currency')
            ->disableOriginalConstructor()
            ->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('JPY');

        $this->priceCurrency->expects($this->atLeastOnce())
            ->method('getCurrency')->willReturn($currency);

        $this->assertEquals(
            101,
            $this->priceRoundPlugin->aroundConvertAndRound(
                $this->priceCurrency,
                $this->closure,
                100.49,
                [],
                null,
                'JPY',
                2
            )
        );
    }

    /**
     * Test for aroundConverterRound (floor)
     *
     * @return void
     */
    public function testAroundConvertRoundFloor()
    {
        $system = $this->system;
        $priceCurrency = $this->priceCurrency;
        $priceRound = $this->priceRoundPlugin;
        $closure = $this->closure;
        $system->expects($this->atLeastOnce())
            ->method('getRoundMethod')
            ->willReturn('floor');
        $system->expects($this->atLeastOnce())
            ->method('getIntegerCurrencies')->willReturn(['JPY']);
        $currency = $this->getMockBuilder('Magento\Directory\Model\Currency')
            ->disableOriginalConstructor()
            ->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('JPY');

        $priceCurrency->expects($this->atLeastOnce())
            ->method('getCurrency')->willReturn($currency);

        $this->assertEquals(
            100,
            $priceRound->aroundConvertAndRound(
                $priceCurrency,
                $closure,
                100.49,
                [],
                null,
                'JPY',
                2
            )
        );
    }

    /**
     * Test for aroundRoundRound (round)
     *
     * @return void
     */
    public function testAroundRoundRound()
    {
        $system = $this->system;
        $priceCurrency = $this->priceCurrency;
        $priceRound = $this->priceRoundPlugin;
        $closure = $this->closure;
        $system->expects($this->atLeastOnce())
            ->method('getRoundMethod')
            ->willReturn('round');
        $system->expects($this->atLeastOnce())
            ->method('getIntegerCurrencies')->willReturn(['JPY']);
        $currency = $this->getMockBuilder('Magento\Directory\Model\Currency')
            ->disableOriginalConstructor()
            ->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('JPY');

        $priceCurrency->expects($this->atLeastOnce())
            ->method('getCurrency')->willReturn($currency);

        $this->assertEquals(
            100,
            $priceRound->aroundRound($priceCurrency, $closure, 100.49, 'USD', 2)
        );
    }

    /**
     * Test for aroundRoundRound (ceil)
     *
     * @return void
     */
    public function testAroundRoundCeil()
    {
        $system = $this->system;
        $priceCurrency = $this->priceCurrency;
        $priceRound = $this->priceRoundPlugin;
        $closure = $this->closure;
        $system->expects($this->atLeastOnce())
            ->method('getRoundMethod')
            ->willReturn('ceil');
        $system->expects($this->atLeastOnce())
            ->method('getIntegerCurrencies')->willReturn(['JPY']);
        $currency = $this->getMockBuilder('Magento\Directory\Model\Currency')
            ->disableOriginalConstructor()
            ->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('JPY');

        $priceCurrency->expects($this->atLeastOnce())
            ->method('getCurrency')->willReturn($currency);

        $this->assertEquals(
            101,
            $priceRound->aroundRound($priceCurrency, $closure, 100.49, 'USD', 2)
        );
    }

    /**
     * Test for aroundRoundRound (floor)
     *
     * @return void
     */
    public function testAroundRoundFloor()
    {
        $system = $this->system;
        $priceCurrency = $this->priceCurrency;
        $priceRound = $this->priceRoundPlugin;
        $closure = $this->closure;

        $system->expects($this->atLeastOnce())
            ->method('getRoundMethod')
            ->willReturn('floor');
        $system->expects($this->atLeastOnce())
            ->method('getIntegerCurrencies')->willReturn(['JPY']);
        $currency = $this->getMockBuilder('Magento\Directory\Model\Currency')
            ->disableOriginalConstructor()
            ->getMock();
        $currency->expects($this->atLeastOnce())
            ->method('getCode')->willReturn('JPY');

        $priceCurrency->expects($this->atLeastOnce())
            ->method('getCurrency')->willReturn($currency);

        $this->assertEquals(
            100,
            $priceRound->aroundRound($priceCurrency, $closure, 100.49, 'USD', 2)
        );
    }
}