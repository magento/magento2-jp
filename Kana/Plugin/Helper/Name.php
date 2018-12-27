<?php

namespace MagentoJapan\Kana\Plugin\Helper;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Helper\View;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Modify customer name according to JP locale requirements.
 */
class Name
{
    /**
     * @var CustomerMetadataInterface
     */
    private $customerMetadataService;

    /**
     * @var ResolverInterface
     */
    private $localeResolver;

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
     * Modify customer name according to JP locale requirements.
     *
     * @param View $subject
     * @param \Closure $proceed
     * @param CustomerInterface $customerData
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetCustomerName(
        View $subject,
        \Closure $proceed,
        CustomerInterface $customerData
    ) {
        $name = '';
        $prefixMetadata = $this->customerMetadataService->getAttributeMetadata('prefix');
        if ($prefixMetadata->isVisible() && $customerData->getPrefix()) {
            $name .= $customerData->getPrefix() . ' ';
        }

        if ($this->localeResolver->getLocale() != 'ja_JP') {
            $name .= $customerData->getFirstname();
        } else {
            $name .= $customerData->getLastname();
        }

        $middleNameMetadata = $this->customerMetadataService->getAttributeMetadata('middlename');
        if ($middleNameMetadata->isVisible() && $customerData->getMiddlename()) {
            $name .= ' ' . $customerData->getMiddlename();
        }

        if ($this->localeResolver->getLocale() != 'ja_JP') {
            $name .= ' ' . $customerData->getLastname();
        } else {
            $name .= ' ' . $customerData->getFirstname();
        }

        $suffixMetadata = $this->customerMetadataService->getAttributeMetadata('suffix');
        if ($suffixMetadata->isVisible() && $customerData->getSuffix()) {
            $name .= ' ' . $customerData->getSuffix();
        }
        return $name;
    }
}
