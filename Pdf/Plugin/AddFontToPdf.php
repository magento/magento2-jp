<?php
namespace MagentoJapan\Pdf\Plugin;

use MagentoJapan\Pdf\ModelConfig\Service;


class AddFontToPdf
{
    /**
     * @var Service
     */
    private $service;

    /**
     * AddFontToPdf constructor.
     * @param Service $service
     */
    public function __construct(
        Service $service
    ) {
        $this->service = $service;
    }

    /**
     * @param $subject
     * @param $page
     * @param array $draw
     * @param array $pageSettings
     * @return array
     */
    public function beforeDrawLineBlocks($subject, $page, array $draw, array $pageSettings = [])
    {
        $newDraw = [];
        if ($this->service->getJapaneseFontIsActive()) {
            $fontpath = $this->service->getJapaneseFont();

            foreach ($draw as &$itemsProp) {
                $lines = $itemsProp['lines'];
                foreach ($lines as &$line) {
                    foreach ($line as &$column) {
                        $column['font_file'] = $fontpath;
                    }
                    $newDraw[] = ['lines' => [$line]];
                }
            }
        } else {
            $newDraw = $draw;
        }


        return [$page, $newDraw, $pageSettings];
    }

}