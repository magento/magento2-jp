<?php
namespace MagentoJapan\Pdf\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;

/**
 * Class Data
 * @package MagentoJapan\Pdf\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
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
     * @param Context $context
     * @param \Magento\Framework\Module\Dir\Reader $dirReader
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Module\Dir\Reader $dirReader
    )
    {
        parent::__construct($context);
        $this->_dir = $dirReader->getModuleDir('', 'MagentoJapan_Pdf') . '/' . self::FONT_DIR;
    }

    /**
     * @param $store
     * @return mixed
     */
    public function getJapaneseFontIsActive($store = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->
        getValue('magentojapan_pdf/font/active', $store);
    }

    /**
     * @param $store
     * @return mixed
     */
    public function getJapaneseFont($store = ScopeInterface::SCOPE_STORE)
    {

        return $this->_dir . $this->scopeConfig->
            getValue('magentojapan_pdf/font/fontname', $store);
    }
}