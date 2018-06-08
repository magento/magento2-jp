<?php
namespace MagentoJapan\Zip2address\Model;

use \Magento\Checkout\Model\ConfigProviderInterface;
use \MagentoJapan\Zip2address\Helper\Data;

class ConfigProvider implements ConfigProviderInterface
{

    private $helper;


    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = [];

        $config['zip2address']['lang'] = $this->helper->getCurrentLocale();

        return $config;
    }


}