<?php
namespace MagentoJapan\Pdf\Test\Unit\Helper;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class DataTest
 * @package MagentoJapan\Pdf\Test\Unit\Helper
 */
class DataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var
     */
    protected $_scopeMock;
    /**
     * @var
     */
    protected $_helper;

    /**
     *
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->_scopeMock =
            $this->getMockBuilder('Magento\Framework\App\Config\ScopeConfigInterface')
                ->disableOriginalConstructor()
                ->getMock();
        $contextMock = $this->getMockBuilder('Magento\Framework\App\Helper\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->_scopeMock);
        $this->_helper =
            $objectManager->getObject('MagentoJapan\Pdf\Helper\Data',
                ['context'=>$contextMock]);
    }

    /**
     *
     */
    public function testGetJapaneseFontIsActive()
    {
        $this->_scopeMock->expects($this->once())
            ->method('getValue')
            ->willReturn('1');
        $this->assertEquals($this->_helper->getJapaneseFontIsActive(), '1');
    }

    /**
     *
     */
    public function testGetJapaneseFont()
    {
        $this->_scopeMock->expects($this->once())
            ->method('getValue')
            ->willReturn('ipag.ttf');
        $this->assertEquals($this->_helper->getJapaneseFont(),
            'ipag.ttf');
    }
}