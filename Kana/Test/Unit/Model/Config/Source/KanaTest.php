<?php
namespace MagentoJapan\Kana\Test\Unit\Model\Config\Source;
use MagentoJapan\Kana\Model\Config\Source\Kana;


/**
 * Class KanaTest
 * @package MagentoJapan\Kana\Test\Unit\Model\Config\Source
 */
class KanaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \MagentoJapan\Kana\Model\Config\Source\Kana
     */
    protected $model;

    /**
     *
     */
    protected function setUp()
    {
        $this->model = new Kana();
    }

    /**
     *
     */
    public function testToOptionArray()
    {
        $this->assertArrayHasKey('1', $this->model->toOptionArray());
    }
}