<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Sales\Model\Order;

use Magento\Sales\Model\Order;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Order should return customer name in correct format.
 */
class CustomerName
{
    /**
     * @var ResolverInterface
     */
    private $localeResolver;

    /**
     * @param ResolverInterface $localeResolver
     */
    public function __construct(
        ResolverInterface $localeResolver
    ) {
        $this->localeResolver = $localeResolver;
    }

    /**
     * Build Japanese name.
     *
     * @param Order $order
     * @param string $customerName
     * @return string
     */
    public function afterGetCustomerName(
        Order $order,
        string $customerName
    ) {
//        if($this->localeResolver->getLocale() != 'ja_JP') {
//            return $customerName;
//        }

        if (!$order->getCustomerLastname()) {
            return (string)__('Guest');
        }

        $customerName = sprintf(
            '%s %s',
            $order->getCustomerLastname(),
            $order->getCustomerFirstname()
        );

        $extensions = $order->getExtensionAttributes();
        if ($extensions === null) {
            return $customerName;
        }

        $customerNamekana = sprintf(
            '%s %s',
            $extensions->getCustomerLastnamekana(),
            $extensions->getCustomerFirstnamekana()
        );
        if (!empty(trim($customerNamekana))) {
            $customerName = sprintf('%s (%s)', $customerName, $customerNamekana);
        }

        return $customerName;
    }
}
