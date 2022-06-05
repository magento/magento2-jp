<?php

namespace CommunityEngineering\JapaneseAddress\Block\Checkout;

use CommunityEngineering\JapaneseAddress\Model\Config\CountryInputConfig;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Model\ScopeInterface;

/**
 * Class AddressFieldsOrder
 *
 * @package CommunityEngineering\JapaneseAddress\Block\Checkout
 */
class AddressFieldsOrder implements LayoutProcessorInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @param \Magento\Framework\Stdlib\ArrayManager $arrayManager
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ArrayManager $arrayManager,
        \Magento\Eav\Model\Config $eavConfig,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->eavConfig = $eavConfig;
        $this->arrayManager = $arrayManager;
    }

    public function process($jsLayout)
    {
        $locale = $this->getStoreLocale();
        if ($locale !== 'ja_JP') {
            return $jsLayout;
        }

        $shippingAddressFieldsetPath = $this->arrayManager->findPath('shipping-address-fieldset', $jsLayout, 'components');
        $shippingAddressFieldsetChildrenPath = $this->arrayManager->findPath('children', $jsLayout, $shippingAddressFieldsetPath);
        if ($shippingAddressFieldsetPath && $shippingAddressFieldsetChildrenPath) {

            $availableAttributes = $this->eavConfig->getEntityAttributes(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS);
            $availableAttributes = $this->sortAttributesByPosition($availableAttributes);
            $orderedAttributes = array_flip($this->getAttributesOrder());
            $undefinedOrderShift = (count($orderedAttributes) + 1) * 10;

            foreach ($availableAttributes as $attributeCode => $attribute) {
                if (isset($orderedAttributes[$attributeCode])) {
                    $position = ($orderedAttributes[$attributeCode] + 1) * 10;
                } else {
                    $position = $undefinedOrderShift;
                    $undefinedOrderShift += 10;
                }

                $attributeCodePath = $this->arrayManager->findPath($attributeCode, $jsLayout, $shippingAddressFieldsetChildrenPath);
                $sortOrderPath = $this->arrayManager->findPath('sortOrder', $jsLayout, $attributeCodePath);
                if ($attributeCodePath && $sortOrderPath) {
                    $jsLayout = $this->arrayManager->set($sortOrderPath, $jsLayout, $position);
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

    /**
     * Get ordered attribute codes.
     *
     * @return array
     */
    private function getAttributesOrder()
    {
        return [
            'prefix',
            'lastname',
            'middlename',
            'firstname',
            'suffix',
            'lastnamekana',
            'firstnamekana',
            'email',
            'country_id',
            'postcode',
            'region_id',
            'region',
            'city',
            'street',
            'telephone',
            'fax',
            'company',
        ];
    }

    /**
     * Sort list of attributes by position.
     *
     * @param array $attributes
     * @return array
     */
    private function sortAttributesByPosition(array $attributes): array
    {
        uasort($attributes, function (AbstractAttribute $a1, AbstractAttribute $a2) {
            return $a1->getData('sort_order') - $a2->getData('sort_order');
        });
        return $attributes;
    }
}
