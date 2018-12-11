<?php

namespace MagentoJapan\Zip2address\Plugin\Checkout\Block\Checkout;

use Magento\Braintree\Model\LocaleResolver;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Checkout plugin for Zip to address resolution
 */
class LayoutProcessor
{
    /**
     * @var ResolverInterface
     */
    private $localeResolver;

    /**
     * @param LocaleResolver $localeResolver
     */
    public function __construct(ResolverInterface $localeResolver)
    {
        $this->localeResolver = $localeResolver;
    }

    /**
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
