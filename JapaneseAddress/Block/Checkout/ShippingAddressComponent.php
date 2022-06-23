<?php

namespace CommunityEngineering\JapaneseAddress\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ShippingAddressComponent
 *
 * @package CommunityEngineering\JapaneseAddress\Block\Checkout
 */
class ShippingAddressComponent implements LayoutProcessorInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @param \Magento\Framework\Stdlib\ArrayManager $arrayManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ArrayManager $arrayManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->arrayManager = $arrayManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function process($jsLayout)
    {
        $locale = $this->getStoreLocale();
        if ($locale !== 'ja_JP') {
            $checkoutStepsPath = $this->arrayManager->findPath('steps', $jsLayout, 'components');
            if ($checkoutStepsPath) {
                $shippingStepAddressListPath = $this->arrayManager->findPath('address-list', $jsLayout, $checkoutStepsPath);
                $childrenNodePath = $this->arrayManager->findPath('children', $jsLayout, $shippingStepAddressListPath);
                $configNodePath = $this->arrayManager->findPath('config', $jsLayout, $shippingStepAddressListPath);

                if ($shippingStepAddressListPath && $childrenNodePath && $configNodePath) {
                    $jsLayout = $this->arrayManager->remove($childrenNodePath, $jsLayout);
                    $jsLayout = $this->arrayManager->remove($configNodePath, $jsLayout);
                }
            }

            $checkoutSidebarPath = $this->arrayManager->findPath('sidebar', $jsLayout, 'components');
            if ($checkoutSidebarPath) {
                $shippingInformationPath = $this->arrayManager->findPath('shipping-information', $jsLayout, $checkoutSidebarPath);
                $shipToPath = $this->arrayManager->findPath('ship-to', $jsLayout, $shippingInformationPath);
                $configNodePath = $this->arrayManager->findPath('config', $jsLayout, $shipToPath);

                if ($shippingInformationPath && $shipToPath && $configNodePath) {
                    $jsLayout = $this->arrayManager->remove($configNodePath, $jsLayout);
                }
            }
        }


        return $jsLayout;
    }

    /**
     * Retrieve current store locale from system configuration.
     *
     * @return mixed
     */
    private function getStoreLocale()
    {
        return $this->scopeConfig->getValue(DirectoryHelper::XML_PATH_DEFAULT_LOCALE, ScopeInterface::SCOPE_STORE);
    }
}
