<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Plugin\Customer\Block\Account\Dashboard;

use Magento\Customer\Block\Account\Dashboard\Info;
use Magento\Framework\Api\CustomAttributesDataInterface;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Display name kana at account dashboard
 */
class InfoNameKana
{
    /**
     * @var ResolverInterface
     */
    protected $localeResolver;

    /**
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     */
    public function __construct(
        ResolverInterface $localeResolver
    ) {
        $this->localeResolver = $localeResolver;
    }

    /**
     * Add kana to name.
     *
     * @param Info $info
     * @param string|null $name
     * @return string
     */
    public function afterGetName(Info $info, $name)
    {
        if ($this->localeResolver->getLocale() !== 'ja_JP') {
            return $name;
        }

        $customer = $info->getCustomer();
        if (!$customer) {
            return $name;
        }

        $firstnamekana = $this->getCustomAttributeValue($customer, 'firstnamekana');
        $lastnamekana = $this->getCustomAttributeValue($customer, 'lastnamekana');
        $namekana = trim(sprintf('%s %s', $lastnamekana, $firstnamekana));

        if (empty($namekana)) {
            return $name;
        } elseif (empty($name)) {
            return $namekana;
        } else {
            return sprintf('%s (%s)', $name, $namekana);
        }
    }

    /**
     * Read custom attribute value
     *
     * @param CustomAttributesDataInterface $container
     * @param string $attributeCode
     * @return null|string
     */
    private function getCustomAttributeValue(CustomAttributesDataInterface $container, string $attributeCode):? string
    {
        $attribute = $container->getCustomAttribute($attributeCode);
        if ($attribute === null) {
            return $attribute;
        }

        return $attribute->getValue();
    }
}
