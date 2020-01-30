<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\ConfigurablePdfFont\Model;

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
     * @param array $data
     */
    public function __construct(
        array $data
    ) {
        if (!isset($data['name'])) {
            throw new \InvalidArgumentException('Font declaration must contain "name" attribute.');
        }
        if (!isset($data['regular'])) {
            throw new \InvalidArgumentException('Font declaration must contain "regular" attribute.');
        }
        foreach (['regular', 'bold', 'italic'] as $fontFile) {
            if (isset($data[$fontFile]) && !$data[$fontFile] instanceof FontFile) {
                throw new \InvalidArgumentException(
                    sprintf('Attribute "%s" should declare instance of "%s"', $fontFile, FontFile::class)
                );
            }
        }

        $this->name = $data['name'];
        $this->regular = $data['regular'];
        $this->bold = $data['bold'] ?? null;
        $this->italic = $data['italic'] ?? null;
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
