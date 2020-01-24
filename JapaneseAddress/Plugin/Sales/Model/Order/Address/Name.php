<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Sales\Model\Order\Address;

use Magento\Sales\Model\Order\Address;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Order addresses should return name in correct format.
 */
class Name
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
     * @param Address $address
     * @param string $name
     * @return string
     */
    public function afterGetName(
        Address $address,
        string $name
    ) {
//        if($this->localeResolver->getLocale() != 'ja_JP') {
//            return $name;
//        }

        $name = '';
        if ($address->getPrefix()) {
            $name .= $address->getPrefix() . ' ';
        }
        $name .= $address->getLastname();
        if ($address->getMiddlename()) {
            $name .= ' ' . $address->getMiddlename();
        }
        $name .= ' ' . $address->getFirstname();
        if ($address->getSuffix()) {
            $name .= ' ' . $address->getSuffix();
        }

        $extensions = $address->getExtensionAttributes();
        if ($extensions === null) {
            return $name;
        }

        $namekana = trim(sprintf('%s %s', $extensions->getLastnamekana(), $extensions->getFirstnamekana()));
        if ($namekana !== '') {
            $name = sprintf('%s (%s)', $name, $namekana);
        }

        return $name;
    }
}
