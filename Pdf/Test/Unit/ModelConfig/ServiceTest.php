<?php
namespace MagentoJapan\Pdf\Test\Unit\ModelConfig;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $scopeMock;
    /**
     * @var \MagentoJapan\Pdf\ModelConfig\Service|\PHPUnit_Framework_MockObject_MockObject
     */
    private $service;

    /**
     *
     */
    protected function setUp()
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
        $this->service =
            $objectManager->getObject('MagentoJapan\Pdf\ModelConfig\Service',
                ['context'=>$contextMock]);
    }

    /**
     *
     */
    public function testGetJapaneseFontIsActive()
    {
        $this->scopeMock->expects($this->once())
            ->method('getValue')
            ->willReturn('1');
        $this->assertEquals($this->service->getJapaneseFontIsActive(), '1');
    }

    /**
     *
     */
    public function testGetJapaneseFont()
    {
        $this->scopeMock->expects($this->once())
            ->method('getValue')
            ->willReturn('ipag.ttf');
        $this->assertEquals($this->service->getJapaneseFont(),
            'ipag.ttf');
    }
}