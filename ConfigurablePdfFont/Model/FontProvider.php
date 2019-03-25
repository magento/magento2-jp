<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\ConfigurablePdfFont\Model;

/**
 * Service which provides list of available fonts.
 *
 * Now configured though DI. If feature will be moved to Magento core new type of configuration should be introduced.
 */
class FontProvider
{
    /**
     * @var Font[]
     */
    private $fonts;

    /**
     * @param Font[] $fonts
     */
    public function __construct(array $fonts)
    {
        $this->fonts = $fonts;
    }

    /**
     * Get list of all available fonts.
     *
     * @return Font[]
     */
    public function getFonts(): array
    {
        return $this->fonts;
    }

    /**
     * Check if font is available.
     *
     * @param string $code
     * @return bool
     */
    public function hasFont(string $code): bool
    {
        return isset($this->fonts[$code]);
    }

    /**
     * Fetch font by code.
     *
     * @param string $code
     * @return Font
     * @throws \OutOfBoundsException if font is not registered.
     */
    public function getFont(string $code): Font
    {
        if (!$this->hasFont($code)) {
            throw new \OutOfBoundsException(sprintf('Unknown font requested "%s".', $code));
        }
        return $this->fonts[$code];
    }
}
