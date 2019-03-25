<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Customer\Model;

use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Config;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Names should have correct format.
 */
class Name
{
    /**
     * @var ResolverInterface
     */
    private $localeResolver;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param ResolverInterface $localeResolver
     * @param Config $config
     */
    public function __construct(
        ResolverInterface $localeResolver,
        Config $config
    ) {
        $this->localeResolver = $localeResolver;
        $this->config = $config;
    }

    /**
     * If enabled Japanese locale then last name should precede first name.
     *
     * @param Customer $customer
     * @param string $name
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterGetName(
        Customer $customer,
        string $name
    ) {
//        if($this->localeResolver->getLocale() != 'ja_JP') {
//            return $name;
//        }

        $name = '';
        if ($this->config->getAttribute('customer', 'prefix')->getIsVisible()
            && $customer->getPrefix()
        ) {
            $name .= $customer->getPrefix() . ' ';
        }
        $name .= $customer->getLastname();
        if ($this->config->getAttribute('customer', 'middlename')->getIsVisible()
            && $customer->getMiddlename()
        ) {
            $name .= ' ' . $customer->getMiddlename();
        }
        $name .= ' ' . $customer->getFirstname();
        if ($this->config->getAttribute('customer', 'suffix')->getIsVisible()
            && $customer->getSuffix()
        ) {
            $name .= ' ' . $customer->getSuffix();
        }
        return $name;
    }
}
