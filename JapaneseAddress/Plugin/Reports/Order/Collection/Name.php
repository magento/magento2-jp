<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Reports\Order\Collection;

use Magento\Reports\Model\ResourceModel\Order\Collection;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\DB\Sql\ExpressionInterface;

/**
 * Use japanese name format for reports.
 */
class Name
{
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $localeResolver;

    /**
     * @var ExpressionInterface
     */
    private $nameSqlExpression;

    /**
     * @param ResolverInterface $localeResolver
     * @param ExpressionInterface $nameSqlExpression
     */
    public function __construct(
        ResolverInterface $localeResolver,
        ExpressionInterface $nameSqlExpression
    ) {
        $this->localeResolver = $localeResolver;
        $this->nameSqlExpression = $nameSqlExpression;
    }

    /**
     * Use specific expression for japanese names in report.
     *
     * @param Collection $subject
     * @param \Closure $proceed
     * @param string $alias
     * @return Collection|mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundJoinCustomerName(
        Collection $subject,
        \Closure $proceed,
        $alias = 'name'
    ) {
//        if($this->localeResolver->getLocale() != 'ja_JP') {
//            return $proceed($alias);
//        }

        $subject->getSelect()->columns([$alias => (string)$this->nameSqlExpression]);
        return $subject;
    }
}
