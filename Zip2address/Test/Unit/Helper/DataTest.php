<?php
declare(strict_types=1);

namespace MagentoJapan\Zip2address\Test\Unit\Helper;

use \Magento\Framework\App\Helper\Context;
use \MagentoJapan\Zip2address\Helper\Data;
use \Magento\Framework\Locale\ResolverInterface;

/**
 * Class DataTest
 * @package MagentoJapan\Zip2address\Test\Unit\Helper
 */
class DataTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\Locale\ResolverInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $localeMock;
    /**
     * @var \Magento\Framework\App\Helper\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    private $contextMock;
    /**
     * @var \MagentoJapan\Zip2address\Helper\Data
     */
    private $helper;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->localeMock = $this->getMockBuilder(ResolverInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->helper = new Data(
            $this->contextMock,
            $this->localeMock
        );
    }

    /**
     * @dataProvider localeDataProvider
     */
    public function testGetCurrentLocale($locale, $expected)
    {
        $this->localeMock->expects(self::once())
            ->method('getLocale')
            ->willReturn($locale);

        $result = $this->helper->getCurrentLocale();
        $this->assertEquals($result, $expected);
    }

    /**
     * @return array
     */
    public function localeDataProvider()
    {
        return [
            [
                'ja_JP',
                'ja'
            ],
            [
                'en_US',
                'en'
            ]
        ];
    }
}
