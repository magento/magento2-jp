<?php
declare(strict_types=1);

namespace MagentoJapan\Kana\Test\Unit\Model\Config\Source;

use MagentoJapan\Kana\Model\Config\Source\Kana;
use PHPUnit\Framework\TestCase;

class KanaTest extends TestCase
{
    /**
     * @var \MagentoJapan\Kana\Model\Config\Source\Kana
     */
    protected $model;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->model = new Kana();
    }

    /**
     * Test options array output.
     */
    public function testToOptionArray()
    {
        $this->assertArrayHasKey('1', $this->model->toOptionArray());
    }
}
