<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace MagentoJapan\Pdf\Model;

/**
 * Font data container.
 */
class Font
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var FontFile
     */
    private $regular;

    /**
     * @var FontFile|null
     */
    private $bold;

    /**
     * @var FontFile|null
     */
    private $italic;

    /**
     * @param string $name
     * @param FontFile $regular
     * @param FontFile|null $bold
     * @param FontFile|null $italic
     */
    public function __construct(
        string $name,
        FontFile $regular,
        ?FontFile $bold = null,
        ?FontFile $italic = null
    ) {
        $this->name = $name;
        $this->regular = $regular;
        $this->bold = $bold;
        $this->italic = $italic;
    }

    /**
     * Get human readable font name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get path to regular font file
     *
     * @return string
     */
    public function getRegular(): string
    {
        return $this->regular->getPath();
    }

    /**
     * Get path to bold font file
     *
     * @return string
     */
    public function getBold(): string
    {
        return $this->bold ? $this->bold->getPath() : $this->getRegular();
    }

    /**
     * Get path to italic font file
     *
     * @return string
     */
    public function getItalic(): string
    {
        return $this->italic ? $this->italic->getPath() : $this->getRegular();
    }
}
