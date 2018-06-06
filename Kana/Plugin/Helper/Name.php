<?php
namespace MagentoJapan\Kana\Plugin\Helper;

use \Magento\Customer\Helper\View;
use \Magento\Customer\Api\CustomerMetadataInterface;
use \Magento\Customer\Api\Data\CustomerInterface;

class Name
{

    private $customerMetadataService;
    private $localeResolver;

    public function __construct(
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        CustomerMetadataInterface $customerMetadataService
    )
    {
        $this->localeResolver = $localeResolver;
        $this->customerMetadataService = $customerMetadataService;
    }

    public function aroundGetCustomerName(
        View $subject,
        \Closure $proceed,
        CustomerInterface $customerData
    )
    {

        $name = '';
        $prefixMetadata = $this->customerMetadataService->getAttributeMetadata('prefix');
        if ($prefixMetadata->isVisible() && $customerData->getPrefix()) {
            $name .= $customerData->getPrefix() . ' ';
        }

        if($this->localeResolver->getLocale() != 'ja_JP') {
            $name .= $customerData->getFirstname();
        } else {
            $name .= $customerData->getLastname();
        }

        $middleNameMetadata = $this->customerMetadataService->getAttributeMetadata('middlename');
        if ($middleNameMetadata->isVisible() && $customerData->getMiddlename()) {
            $name .= ' ' . $customerData->getMiddlename();
        }

        if($this->localeResolver->getLocale() != 'ja_JP') {
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