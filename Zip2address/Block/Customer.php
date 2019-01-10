<?php
declare(strict_types=1);

namespace MagentoJapan\Zip2address\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\View\Element\Template\Context;

/**
 * Customer zip validation block.
 *
 * @api
 */
class Customer extends Template
{
    /**
     * @var ResolverInterface
     */
    private $localeResolver;

    /**
     * @param Context $context
     * @param ResolverInterface $localeResolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        ResolverInterface $localeResolver,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->localeResolver = $localeResolver;
    }

    /**
     * Get locale.
     *
     * @return string
     */
    public function getCurrentLocale(): string
    {
        return $this->localeResolver->getLocale();
    }
}
