<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Test\Unit\Model;

use CommunityEngineering\CurrencyPrecision\Model\Config\CurrencyRoundingConfig;
use CommunityEngineering\CurrencyPrecision\Model\Config\Source\RoundingMode;
use CommunityEngineering\CurrencyPrecision\Model\CurrencyRounding;
use PHPUnit\Framework\TestCase;

/**
 * Currency Rounding Tests
 */
class CurrencyRoundingTest extends TestCase
{
    /**
     * @dataProvider currencyPrecisions
     */
    public function testGetCurrencyPrecision($currencyCode, $expectedPrecision)
    {
        $config = $this->getMockBuilder(CurrencyRoundingConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $currencyPrecision = new CurrencyRounding($config);
        $actualPrecision = $currencyPrecision->getPrecision($currencyCode);
        $this->assertEquals($expectedPrecision, $actualPrecision);
    }

    /**
     * @dataProvider roundingExamples
     */
    public function testRound(
        string $currency,
        string $roundingMode,
        float $amount,
        float $expectedResult
    ) {
        $config = $this->getMockBuilder(CurrencyRoundingConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $config->method('getRoundingMode')
            ->willReturn($roundingMode);

        $handler = new CurrencyRounding($config);
        $actualResult = $handler->round($currency, $amount);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * List of currencies with their precisions.
     *
     * @return array
     */
    public function currencyPrecisions(): array
    {
        return [
            ['USD', 2],
            ['JPY', 0],
        ];
    }

    /**
     * Table of rounding for currencies in different modes.
     *
     * @return array
     */
    public function roundingExamples(): array
    {
        return [
            ['USD', RoundingMode::UP, 1111.237, 1111.24],
            ['USD', RoundingMode::CEILING, 1111.237, 1111.24],
            ['USD', RoundingMode::UP, -1111.237, -1111.24],
            ['USD', RoundingMode::CEILING, -1111.237, -1111.23],
            ['USD', RoundingMode::DOWN, 1111.237, 1111.23],
            ['USD', RoundingMode::FLOOR, 1111.237, 1111.23],
            ['USD', RoundingMode::DOWN, -1111.237, -1111.23],
            ['USD', RoundingMode::FLOOR, -1111.237, -1111.24],
            ['USD', RoundingMode::HALFUP, 1111.235, 1111.24],
            ['USD', RoundingMode::HALFUP, 1111.245, 1111.25],
            ['USD', RoundingMode::HALFEVEN, 1111.235, 1111.24],
            ['USD', RoundingMode::HALFEVEN, 1111.245, 1111.24],
            ['USD', RoundingMode::HALFDOWN, 1111.235, 1111.23],
            ['USD', RoundingMode::HALFDOWN, 1111.245, 1111.24],

            ['JPY', RoundingMode::UP, 1234.7, 1235],
            ['JPY', RoundingMode::CEILING, 1234.7, 1235],
            ['JPY', RoundingMode::UP, -1234.7, -1235],
            ['JPY', RoundingMode::CEILING, -1234.7, -1234],
            ['JPY', RoundingMode::DOWN, 1234.7, 1234],
            ['JPY', RoundingMode::FLOOR, 1234.7, 1234],
            ['JPY', RoundingMode::DOWN, -1234.7, -1234],
            ['JPY', RoundingMode::FLOOR, -1234.7, -1235],
            ['JPY', RoundingMode::HALFUP, 1233.5, 1234],
            ['JPY', RoundingMode::HALFUP, 1234.5, 1235],
            ['JPY', RoundingMode::HALFEVEN, 1233.5, 1234],
            ['JPY', RoundingMode::HALFEVEN, 1234.5, 1234],
            ['JPY', RoundingMode::HALFDOWN, 1233.5, 1233],
            ['JPY', RoundingMode::HALFDOWN, 1234.5, 1234],
        ];
    }
}
