<?php
namespace MagentoJapan\Kana\Plugin\Reports\Order\Collection;

use Magento\Reports\Model\ResourceModel\Order\Collection;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Modify customers full name according to JP locale requirements.
 */
class ModifyName
{
    /**
     * @var ResolverInterface
     */
    private $localeResolver;

    /**
     * @var \Magento\Eav\Model\Config
     */
    private $config;

    /**
     * @param ResolverInterface $localeResolver
     * @param \Magento\Eav\Model\Config $config
     */
    public function __construct(
        ResolverInterface $localeResolver,
        \Magento\Eav\Model\Config $config
    ) {
        $this->localeResolver = $localeResolver;
        $this->config = $config;
    }

    /**
     * Modify customers full name according to JP locale requirements.
     *
     * @param Collection $subject
     * @param \Closure $proceed
     * @param string $alias
     * @return Collection|mixed
     */
    public function aroundJoinCustomerName(
        Collection $subject,
        \Closure $proceed,
        $alias = 'name'
    ) {
        if ($this->localeResolver->getLocale() != 'ja_JP') {
            return $proceed($alias);
        } else {
            $fields = ['main_table.customer_lastname', 'main_table.customer_firstname'];
            $fieldConcat = $subject->getConnection()->getConcatSql($fields, ' ');
            $subject->getSelect()->columns([$alias => $fieldConcat]);
            return $subject;
        }
    }
}
