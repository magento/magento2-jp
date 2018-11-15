<?php
namespace MagentoJapan\Pdf\Plugin;

use Magento\Framework\App\Filesystem\DirectoryList;
use MagentoJapan\Pdf\Helper\Data;


/**
 * Class AddFontToPdf
 * @package MagentoJapan\Pdf\Plugin
 */
class AddFontToPdf
{

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface
     */
    protected $_rootDirectory;

    /**
     * AddFontToPdf constructor.
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        Data $_helper
    )
    {
        $this->_rootDirectory = $filesystem->getDirectoryRead(DirectoryList::ROOT);
        $this->_helper = $_helper;
    }

    /**
     * @return \MagentoJapan\Pdf\Helper\Data
     */
    public function getHelper()
    {
        return $this->_helper;
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
        if ($this->getHelper()->getJapaneseFontIsActive()) {
            $fontpath = $this->getHelper()->getJapaneseFont();

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