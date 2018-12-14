<?php

namespace MagentoJapan\Price\Test\Unit\Model\Config\Source;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class RoundTest extends TestCase
{
    /**
     * Round configuration class
     *
     * @var \MagentoJapan\Price\Model\Config\Source\Round
     */
    protected $model;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->model = $objectManager->getObject(
            'MagentoJapan\Price\Model\Config\Source\Round'
        );
    }

    /**
     * Test toOptionArray
     *
     * @return void
     */
    public function testToOptionArray()
    {
        $this->assertArrayHasKey('round', $this->model->toOptionArray());
    }
}