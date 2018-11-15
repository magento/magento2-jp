<?php
/**
 * Created by PhpStorm.
 * User: tsuchiya
 * Date: 2016/07/01
 * Time: 15:23
 */
namespace MagentoJapan\Pdf\Model\Config\Source;

/**
 * Class JapaneseFont
 * @package MagentoJapan\Pdf\Model\Config\Source
 */
class JapaneseFont implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * font file path
     */
    const FONT_DIR = 'lib/fonts/';

    /**
     * @var string
     */
    protected $_dir;

    /**
     * JapaneseFont constructor.
     * @param \Magento\Framework\Module\Dir\Reader $dirReader
     */
    public function __construct(
        \Magento\Framework\Module\Dir\Reader $dirReader
    )
    {
        $this->_dir = $dirReader->getModuleDir('', 'MagentoJapan_Pdf') . '/' . self::FONT_DIR;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'ipag.ttf', 'label' => __('IPA Gothic')],
            ['value' => 'ipagp.ttf', 'label' => __('IPA P Gothic')],
            ['value' => 'ipam.ttf', 'label' => __('IPA Mincho')],
            ['value' => 'ipamp.ttf', 'label' => __('IPA P Mincho')]
        ];
    }
}