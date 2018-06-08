<?php
namespace MagentoJapan\Kana\Plugin\Reports\Order\Collection;

use \Magento\Reports\Model\ResourceModel\Order\Collection;
use \Magento\Framework\Locale\ResolverInterface;

class ModifyName
{
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $localeResolver;

    private $config;

    /**
     * @param ResolverInterface $localeResolver
     * @param \Magento\Eav\Model\Config $config
     */
    public function __construct(
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magento\Eav\Model\Config $config
    ) {
        $this->localeResolver = $localeResolver;
        $this->config = $config;
    }

    public function aroundJoinCustomerName(
        Collection $subject,
        \Closure $proceed,
        $alias = 'name'
    ) {
        if($this->localeResolver->getLocale() != 'ja_JP') {
            return $proceed($alias);
        } else {
            $fields = ['main_table.customer_lastname', 'main_table.customer_firstname'];
            $fieldConcat = $subject->getConnection()->getConcatSql($fields, ' ');
            $subject->getSelect()->columns([$alias => $fieldConcat]);
            return $subject;
        }


    }
}