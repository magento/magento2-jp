<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\ConfigurablePdfFont\Model;

use Magento\Framework\Component\ComponentRegistrar;

/**
 * Font file data object.
 *
 * Font file may be specified as a resource string descriptor which may be path to font file for Magento root
 * (recommended only for fonts provided by library bundled with Magento) or in format <Vendor_Module>::font_file.
 * Modules MUST contain fonts in lib/fonts folder.
 */
class FontFile
{
    /**
     * Delimiter that may be used to specify font in module in format Vendor_Module::font_name
     */
    const MODULE_FONT_NAME_DELIMITER = '::';

    /**
     * Conventional path for font files in module structure.
     */
    const FONT_DIR = 'lib/fonts/';

    /**
     * @var ComponentRegistrar
     */
    private $componentRegistrar;

    /**
     * @var string
     */
    private $relativePath;

    /**
     * @var string
     */
    private $absolutePath;

    /**
     * @param ComponentRegistrar $componentRegistrar
     * @param string $path
     */
    public function __construct(
        ComponentRegistrar $componentRegistrar,
        string $path
    ) {
        $this->componentRegistrar = $componentRegistrar;
        $this->relativePath = $path;
    }

    /**
     * Get font file path.
     *
     * @return string
     */
    public function getPath(): string
    {
        if ($this->absolutePath === null) {
            $this->absolutePath = $this->getAbsolutePath();
        }
        return $this->absolutePath;
    }

    /**
     * Build font absolute path.
     *
     * If font path provided as relative to Magento root it returned as is.
     *
     * @return string
     */
    private function getAbsolutePath(): string
    {
        $parsed = $this->parseFontName($this->relativePath);
        if (!isset($parsed['module'])) {
            return $parsed['font'];
        }

        $modulePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $parsed['module']);

        $parts = [
            $modulePath,
            self::FONT_DIR,
            $parsed['font'],
        ];
        $parts = array_map(function ($pathPart) {
            return trim($pathPart, DIRECTORY_SEPARATOR);
        }, $parts);

        $path = DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, $parts);
        return $path;
    }

    /**
     * Parse font name to detect module name and font file name.
     *
     * @param string $font
     * @return array
     */
    private function parseFontName(string $font): array
    {
        $parts = explode(self::MODULE_FONT_NAME_DELIMITER, $font, 2);
        if (count($parts) < 2) {
            array_unshift($parts, null);
        }

        $parsed = array_combine(['module', 'font'], $parts);
        return $parsed;
    }
}
