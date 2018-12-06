<?php
namespace MagentoJapan\Pdf\ModelConfig;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Service
{
    /**
     * font file path
     */
    const FONT_DIR = 'lib/fonts/';

    const XML_IS_ACTIVE_PATH = 'magentojapan_pdf/font/active';

    const XML_FONT_NAME_PATH = 'magentojapan_pdf/font/fontname';

    /**
     * @var string
     */
    private $dir;

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    private $dirReader;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\Module\Dir\Reader $dirReader
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\Module\Dir\Reader $dirReader,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->dirReader = $dirReader;
        $this->scopeConfig = $scopeConfig;
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

        return $this->getAbsoluteFontDir() . $this->scopeConfig->
            getValue('magentojapan_pdf/font/fontname', $store);
    }

    /**
     * @return string
     */
    private function getAbsoluteFontDir()
    {
        if(!$this->dir) {
            $this->dir = $this->dirReader->getModuleDir('', 'MagentoJapan_Pdf') . '/' . self::FONT_DIR;
        }

        return $this->dir;
    }
}