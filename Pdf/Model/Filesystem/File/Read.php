<?php
declare(strict_types=1);

namespace MagentoJapan\Pdf\Model\Filesystem\File;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Filesystem\Directory\PathValidatorInterface;
use MagentoJapan\Pdf\ModelConfig\Service;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Filesystem\File\ReadFactory;
use Magento\Framework\Filesystem\Directory\Read as BaseRead;

/**
 * Override path resolution for the Magento's built-in PDF fonts based on module configuration.
 */
class Read extends BaseRead
{
    /**
     * @var Service
     */
    private $jpFontService;

    /**
     * @param ReadFactory $fileFactory
     * @param DriverInterface $driver
     * @param string $path
     * @param PathValidatorInterface $pathValidator
     * @param Service $jpFontService
     */
    public function __construct(
        ReadFactory $fileFactory,
        DriverInterface $driver,
        string $path,
        ?PathValidatorInterface $pathValidator = null,
        ?Service $jpFontService = null
    ) {
        $this->jpFontService = $jpFontService ?? ObjectManager::getInstance()->get(Service::class);
        parent::__construct($fileFactory, $driver, $path, $pathValidator);
    }

    /**
     * Override path resolution for build-in fonts with JP fonts when needed.
     *
     * {@inheritdoc}
     */
    public function getAbsolutePath($path = null, $scheme = null)
    {
        if (is_string($path) && in_array($path, $this->jpFontService->getFontsToOverride())) {
            $path = $this->jpFontService->getJapaneseFontPath($path);
        }

        return parent::getAbsolutePath($path, $scheme);
    }
}
