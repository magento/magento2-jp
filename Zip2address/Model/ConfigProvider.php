<?php
namespace MagentoJapan\Zip2address\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use MagentoJapan\Zip2address\Helper\Data;

/**
 * Configuration provider for Zip2Address in checkout.
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        $config = [];

        $config['zip2address']['lang'] = $this->helper->getCurrentLocale();

        return $config;
    }
}
