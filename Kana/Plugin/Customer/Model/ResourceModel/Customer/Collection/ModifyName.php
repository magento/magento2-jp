<?php
declare(strict_types=1);

namespace MagentoJapan\Kana\Plugin\Customer\Model\ResourceModel\Customer\Collection;

use Magento\Customer\Model\ResourceModel\Customer\Collection;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Modify full name to JP locale.
 */
class ModifyName
{
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $localeResolver;

    /**
     * @var \Magento\Framework\DataObject\Copy\Config
     */
    private $fieldsetConfig;

    /**
     * @param ResolverInterface $localeResolver
     * @param \Magento\Framework\DataObject\Copy\Config $fieldsetConfig
     */
    public function __construct(
        ResolverInterface $localeResolver,
        \Magento\Framework\DataObject\Copy\Config $fieldsetConfig
    ) {
        $this->localeResolver = $localeResolver;
        $this->fieldsetConfig = $fieldsetConfig;
    }

    /**
     * Modify full name for JP locale.
     *
     * @param \Magento\Customer\Model\ResourceModel\Customer\Collection $subject
     * @param \Closure $proceed
     * @return \Magento\Customer\Model\ResourceModel\Customer\Collection
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundAddNameToSelect(
        Collection $subject,
        \Closure $proceed
    ) {
        $fields = [];
        $customerAccount = $this->fieldsetConfig->getFieldset('customer_account');
        foreach ($customerAccount as $code => $field) {
            if (isset($field['name'])) {
                $fields[$code] = $code;
            }
        }

        $connection = $subject->getConnection();
        $concatenate = [];
        if (isset($fields['prefix'])) {
            $concatenate[] = $connection->getCheckSql(
                '{{prefix}} IS NOT NULL AND {{prefix}} != \'\'',
                $connection->getConcatSql(['LTRIM(RTRIM({{prefix}}))', '\' \'']),
                '\'\''
            );
        }
        if ($this->localeResolver->getLocale() != 'ja_JP') {
            $concatenate[] = 'LTRIM(RTRIM({{firstname}}))';
        } else {
            $concatenate[] = 'LTRIM(RTRIM({{lastname}}))';
        }

        $concatenate[] = '\' \'';
        if (isset($fields['middlename'])) {
            $concatenate[] = $connection->getCheckSql(
                '{{middlename}} IS NOT NULL AND {{middlename}} != \'\'',
                $connection->getConcatSql(['LTRIM(RTRIM({{middlename}}))', '\' \'']),
                '\'\''
            );
        }
        if ($this->localeResolver->getLocale() != 'ja_JP') {
            $concatenate[] = 'LTRIM(RTRIM({{lastname}}))';
        } else {
            $concatenate[] = 'LTRIM(RTRIM({{firstname}}))';
        }
        if (isset($fields['suffix'])) {
            $concatenate[] = $connection->getCheckSql(
                '{{suffix}} IS NOT NULL AND {{suffix}} != \'\'',
                $connection->getConcatSql(['\' \'', 'LTRIM(RTRIM({{suffix}}))']),
                '\'\''
            );
        }

        $nameExpr = $connection->getConcatSql($concatenate);

        $subject->addExpressionAttributeToSelect('name', $nameExpr, $fields);

        return $subject;
    }
}
