<?php
namespace MagentoJapan\Zip2address\Plugin\Checkout\Block\Checkout;

use Magento\Store\Model\ScopeInterface;
use MagentoJapan\Zip2address\Helper\Data;

class LayoutProcessor
{

    /**
     * @var \MagentoJapan\Zip2address\Helper\Data
     */
    private $helper;


    /**
     * LayoutProcessor constructor.
     * @param \MagentoJapan\Kana\Helper\Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
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
                if($key == 'postcode') {
                    $billingElement['component'] = 'MagentoJapan_Zip2address/js/ui/form/element/post-code';
                }

            }
        }
        return $jsLayout;
    }

}