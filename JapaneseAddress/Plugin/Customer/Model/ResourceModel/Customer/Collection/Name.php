<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Customer\Model\ResourceModel\Customer\Collection;

use Magento\Customer\Model\ResourceModel\Customer\Collection;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\DataObject\Copy\Config;

/**
 * Collection should return names in correct format.
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
    private $fieldsetConfig;

    /**
     * ModifyName constructor.
     * @param ResolverInterface $localeResolver
     * @param Config $fieldsetConfig
     */
    public function __construct(
        ResolverInterface $localeResolver,
        Config $fieldsetConfig
    ) {
        $this->localeResolver = $localeResolver;
        $this->fieldsetConfig = $fieldsetConfig;
    }

    /**
     * If enabled Japanese locale then last name should precede first name.
     *
     * @param Collection $collection
     * @param \Closure $proceed
     * @return Collection|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundAddNameToSelect(Collection $collection, \Closure $proceed)
    {
//        if($this->localeResolver->getLocale() != 'ja_JP') {
//            return $proceed();
//        }

        $fields = [];
        $customerAccount = $this->fieldsetConfig->getFieldset('customer_account');
        foreach ($customerAccount as $code => $field) {
            if (isset($field['name'])) {
                $fields[$code] = $code;
            }
        }

        $connection = $collection->getConnection();
        $concatenate = [];
        if (isset($fields['prefix'])) {
            $concatenate[] = $connection->getCheckSql(
                '{{prefix}} IS NOT NULL AND {{prefix}} != \'\'',
                $connection->getConcatSql(['LTRIM(RTRIM({{prefix}}))', '\' \'']),
                '\'\''
            );
        }
        $concatenate[] = 'LTRIM(RTRIM({{lastname}}))';
        $concatenate[] = '\' \'';
        if (isset($fields['middlename'])) {
            $concatenate[] = $connection->getCheckSql(
                '{{middlename}} IS NOT NULL AND {{middlename}} != \'\'',
                $connection->getConcatSql(['LTRIM(RTRIM({{middlename}}))', '\' \'']),
                '\'\''
            );
        }
        $concatenate[] = 'LTRIM(RTRIM({{firstname}}))';
        if (isset($fields['suffix'])) {
            $concatenate[] = $connection->getCheckSql(
                '{{suffix}} IS NOT NULL AND {{suffix}} != \'\'',
                $connection->getConcatSql(['\' \'', 'LTRIM(RTRIM({{suffix}}))']),
                '\'\''
            );
        }

        $nameExpr = $connection->getConcatSql($concatenate);

        $collection->addExpressionAttributeToSelect('name', $nameExpr, $fields);

        return $collection;
    }
}
