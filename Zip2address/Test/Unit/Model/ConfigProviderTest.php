<?php
namespace MagentoJapan\Zip2address\Test\Unit\Model;

use MagentoJapan\Zip2address\Model\ConfigProvider;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Class ConfigProviderTest
 */
class ConfigProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var ResolverInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $localeResolver;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->localeResolver = $this->getMockBuilder(ResolverInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->configProvider = new ConfigProvider(
            $this->localeResolver
        );
    }

    /**
     * @param $locale string
     * @param $expected string
     * @dataProvider localeDataProvider
     */
    public function testGetConfig($locale, $expected)
    {
        $this->localeResolver->expects($this->once())
            ->method('getLocale')
            ->willReturn($locale);

        $result = $this->configProvider->getConfig();
        $this->assertEquals($result, $expected);
    }

    /**
     * @return array
     */
    public function localeDataProvider(): array
    {
        return [
            [
                'ja_JP',
                [
                    'zip2address' => [
                        'lang' => 'ja_JP'
                    ]
                ],
                'en_US',
                [
                    'zip2address' => [
                        'lang' => 'en_US'
                    ]
                ]
            ]
        ];
    }
}
