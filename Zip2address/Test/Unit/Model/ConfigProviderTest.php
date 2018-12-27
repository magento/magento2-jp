<?php
namespace MagentoJapan\Zip2address\Test\Unit\Model;

use \MagentoJapan\Zip2address\Helper\Data;
use \MagentoJapan\Zip2address\Model\ConfigProvider;
use \Magento\Framework\Locale\ResolverInterface;

/**
 * Class ConfigProviderTest
 * @package MagentoJapan\Zip2address\Test\Unit\Helper
 */
class ConfigProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MagentoJapan\Zip2address\Model\ConfigProvider
     */
    private $configProvider;
    /**
     * @var \MagentoJapan\Zip2address\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    private $helperMock;

    /**
     * setup
     */
    protected function setUp()
    {
        $this->helperMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configProvider = new ConfigProvider(
            $this->helperMock
        );

    }

    /**
     * @covers MagentoJapan\Zip2address\Helper\Data::getCurrentLocale
     *
     * @dataProvider localeDataProvider
     */
    public function testGetConfig($locale, $expected)
    {
        $this->helperMock->expects(self::once())
            ->method('getCurrentLocale')
            ->willReturn($locale);

        $result = $this->configProvider->getConfig();
        $this->assertEquals($result, $expected);
    }

    /**
     * @return array
     */
    public function localeDataProvider()
    {
        return [
            [
                'ja',
                ['zip2address' =>
                    ['lang' => 'ja']
                ]
            ],
            [
                'en',
                ['zip2address' =>
                    ['lang' => 'en']
                ]
            ]
        ];
    }

}