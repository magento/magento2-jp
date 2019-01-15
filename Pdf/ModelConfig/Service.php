<?php
declare(strict_types=1);

namespace MagentoJapan\Pdf\ModelConfig;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * JP PDF font configuration service.
 */
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
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ComponentRegistrar
     */
    private $componentRegistrar;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ComponentRegistrar $componentRegistrar
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ComponentRegistrar $componentRegistrar
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->componentRegistrar = $componentRegistrar;
    }

    /**
     * Check if JP font override is enabled.
     *
     * @param $store
     * @return mixed
     */
    public function getJapaneseFontIsActive($store = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue('magentojapan_pdf/font/active', $store);
    }

    /**
     * Get configured JP font name.
     *
     * @param $store
     * @return mixed
     */
    public function getJapaneseFont($store = ScopeInterface::SCOPE_STORE)
    {
        return $this->getAbsoluteFontDir() . $this->scopeConfig->getValue('magentojapan_pdf/font/fontname', $store);
    }

    /**
     * Get absolute directory for JP fonts inside the module.
     *
     * @return string
     */
    private function getAbsoluteFontDir(): string
    {
        if (!$this->dir) {
            $modulePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, 'MagentoJapan_Pdf');
            $this->dir = $modulePath . DIRECTORY_SEPARATOR . self::FONT_DIR;
        }

        return $this->dir;
    }
}
