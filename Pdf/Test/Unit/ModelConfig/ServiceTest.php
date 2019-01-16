<?php
declare(strict_types=1);

namespace MagentoJapan\Pdf\Test\Unit\ModelConfig;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use MagentoJapan\Pdf\ModelConfig\Service;
use PHPUnit\Framework\TestCase;

/**
 * JP Fonts service test.
 */
class ServiceTest extends TestCase
{
    /**
     * @var Service
     */
    private $service;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->service = $objectManager->getObject(
            Service::class,
            [
                'fontOverrides' => [
                    'en-font.ttf' => 'jp-font.ttf'
                ]
            ]
        );
    }

    /**
     * @return void
     */
    public function testGetJapaneseFontPath()
    {
        $path = $this->service->getJapaneseFontPath('en-font.ttf');

        $this->assertEquals(DIRECTORY_SEPARATOR . Service::FONT_DIR . "jp-font.ttf", $path);
    }
}
