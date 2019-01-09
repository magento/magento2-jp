<?php
declare(strict_types=1);


namespace MagentoJapan\Zip2address\Plugin\Checkout\Block\Checkout;

use MagentoJapan\Zip2address\Helper\Data;

/**
 * Update checkout layout for Zip2Address.
 */
class LayoutProcessor
{
    /**
     * @var \MagentoJapan\Zip2address\Helper\Data
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
     * Update checkout layout for Zip2Address.
     *
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        $payments =& $jsLayout['components']['checkout']['children']
        ['steps']['children']['billing-step']['children']['payment']
        ['children']['payments-list']['children'];

        foreach ($payments as &$method) {
            $elements =& $method['children']['form-fields']['children'];
            if (!is_array($elements)) {
                continue;
            }
            foreach ($elements as $key => &$billingElement) {
                if ($key === 'postcode') {
                    $billingElement['component'] = 'MagentoJapan_Zip2address/js/ui/form/element/post-code';
                }
            }
        }
        return $jsLayout;
    }
}
