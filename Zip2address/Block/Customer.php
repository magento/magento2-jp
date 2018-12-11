<?php
namespace MagentoJapan\Zip2address\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\View\Element\Template\Context;

/**
 * Customer zip validation block.
 */
class Customer extends Template
{
    /**
     * @var ResolverInterface
     */
    private $localeResolver;

    /**
     * Customer constructor.
     * @param ResolverInterface $localeResolver
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        ResolverInterface $localeResolver,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->localeResolver = $localeResolver;
    }

    /**
     * @return string
     */
    public function getCurrentLocale(): string
    {
        return $this->localeResolver->getLocale();
    }
}
