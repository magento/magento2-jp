<?php
declare(strict_types=1);

namespace MagentoJapan\Pdf\ModelConfig;

use Magento\Framework\Component\ComponentRegistrar;
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
     * @var array
     */
    private $fontOverrides = [];

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ComponentRegistrar $componentRegistrar
     * @param array $fontOverrides
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ComponentRegistrar $componentRegistrar,
        array $fontOverrides = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->componentRegistrar = $componentRegistrar;
        $this->fontOverrides = $fontOverrides;
    }

    /**
     * Get JP font file path.
     *
     * @param string $fontName
     * @return string
     */
    public function getJapaneseFontPath(string $fontName): string
    {
        return $this->getAbsoluteFontDir() . $this->fontOverrides[$fontName];
    }

    /**
     * Get a list of JP fonts overriding Core fonts.
     *
     * @return array
     */
    public function getFontsToOverride(): array
    {
        return array_keys($this->fontOverrides);
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
