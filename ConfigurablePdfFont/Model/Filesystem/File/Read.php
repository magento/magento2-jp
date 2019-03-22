<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\ConfigurablePdfFont\Model\Filesystem\File;

use Magento\Framework\Filesystem\Directory\PathValidatorInterface;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Filesystem\File\ReadFactory;
use Magento\Framework\Filesystem\Directory\Read as BaseRead;
use CommunityEngineering\ConfigurablePdfFont\Model\Config\Service;
use CommunityEngineering\ConfigurablePdfFont\Model\Filesystem\FontFilesRewriter;

/**
 * Override path resolution for the Magento's built-in PDF fonts based on module configuration.
 */
class Read extends BaseRead
{
    /**
     * @var FontFilesRewriter
     */
    private $filesRewriter;

    /**
     * @param ReadFactory $fileFactory
     * @param DriverInterface $driver
     * @param string $path
     * @param PathValidatorInterface $pathValidator
     * @param FontFilesRewriter $filesRewriter
     */
    public function __construct(
        ReadFactory $fileFactory,
        DriverInterface $driver,
        string $path,
        PathValidatorInterface $pathValidator,
        FontFilesRewriter $filesRewriter
    ) {
        parent::__construct($fileFactory, $driver, $path, $pathValidator);
        $this->filesRewriter = $filesRewriter;
    }

    /**
     * Override path resolution for build-in fonts with JP fonts when needed.
     *
     * @param string $path
     * @param string $scheme
     * @return string
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    public function getAbsolutePath($path = null, $scheme = null)
    {
        $path = $this->filesRewriter->rewrite($path);
        return parent::getAbsolutePath($path, $scheme);
    }
}
