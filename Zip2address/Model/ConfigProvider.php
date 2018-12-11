<?php
namespace MagentoJapan\Zip2address\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Locale configuration provider
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var ResolverInterface
     */
    private $localeResolver;

    /**
     * @param ResolverInterface $localeResolver
     */
    public function __construct(ResolverInterface $localeResolver)
    {
        $this->localeResolver = $localeResolver;
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        $config = [];
        $config['zip2address']['lang'] = $this->localeResolver->getLocale();

        return $config;
    }
}
