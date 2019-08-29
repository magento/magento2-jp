<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\ConfigurablePdfFont\Model\Filesystem;

use Magento\Framework\Component\ComponentRegistrar;
use CommunityEngineering\ConfigurablePdfFont\Model\Config\FontConfig;

/**
 * Rewrite PDF font file paths.
 *
 * If functionality will be moved to Magento Framework Interface should be extracted to allow override other file types.
 */
class FontFilesRewriter
{
    /**
     * @var ComponentRegistrar
     */
    private $componentRegistrar;

    /**
     * @var FontConfig
     */
    private $fontConfig;

    /**
     * @var callable
     */
    private $handler;

    /**
     * @param ComponentRegistrar $componentRegistrar
     * @param FontConfig $fontConfig
     */
    public function __construct(
        ComponentRegistrar $componentRegistrar,
        FontConfig $fontConfig
    ) {
        $this->componentRegistrar = $componentRegistrar;
        $this->fontConfig = $fontConfig;
    }

    /**
     * @inheritdoc
     */
    public function rewrite(string $path): string
    {
        if (!isset($this->handler)) {
            $this->handler = $this->createRewriteHandler();
        }

        $handler = $this->handler;
        $rewrittenPath = $handler($path);
        return $rewrittenPath;
    }

    /**
     * Create rewrite handler based on configuration.
     *
     * @return \Closure
     */
    private function createRewriteHandler(): \Closure
    {
        $defaultFont = $this->fontConfig->getDefaultFont();
        $activeFont = $this->fontConfig->getActiveFont();
        if ($activeFont === $defaultFont) {
            return function ($path) {
                return $path;
            };
        }

        $map = [
            $defaultFont->getRegular() => $activeFont->getRegular(),
            $defaultFont->getBold() => $activeFont->getBold(),
            $defaultFont->getItalic() => $activeFont->getItalic(),
        ];
        return function ($path) use ($map) {
            if (isset($map[$path])) {
                return $map[$path];
            }
            return $path;
        };
    }
}
