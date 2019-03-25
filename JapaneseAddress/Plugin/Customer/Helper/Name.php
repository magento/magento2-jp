<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Customer\Helper;

use Magento\Customer\Helper\View;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Change customer name format for Japan.
 */
class Name
{
    /**
     * @var ResolverInterface
     */
    private $localeResolver;

    /**
     * @var CustomerMetadataInterface
     */
    private $customerMetadataService;

    /**
     * @param ResolverInterface $localeResolver
     * @param CustomerMetadataInterface $customerMetadataService
     */
    public function __construct(
        ResolverInterface $localeResolver,
        CustomerMetadataInterface $customerMetadataService
    ) {
        $this->localeResolver = $localeResolver;
        $this->customerMetadataService = $customerMetadataService;
    }

    /**
     * If enabled Japanese locale then last name should precede first name.
     *
     * @param View $subject
     * @param string $name
     * @param CustomerInterface $customerData
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetCustomerName(
        View $subject,
        string $name,
        CustomerInterface $customerData
    ) {
//        if($this->localeResolver->getLocale() != 'ja_JP') {
//            return $name;
//        }

        $name = '';
        $prefixMetadata = $this->customerMetadataService->getAttributeMetadata('prefix');
        if ($prefixMetadata->isVisible() && $customerData->getPrefix()) {
            $name .= $customerData->getPrefix() . ' ';
        }

        $name .= $customerData->getLastname();

        $middleNameMetadata = $this->customerMetadataService->getAttributeMetadata('middlename');
        if ($middleNameMetadata->isVisible() && $customerData->getMiddlename()) {
            $name .= ' ' . $customerData->getMiddlename();
        }

        $name .= ' ' . $customerData->getFirstname();

        $suffixMetadata = $this->customerMetadataService->getAttributeMetadata('suffix');
        if ($suffixMetadata->isVisible() && $customerData->getSuffix()) {
            $name .= ' ' . $customerData->getSuffix();
        }

        return $name;
    }
}
