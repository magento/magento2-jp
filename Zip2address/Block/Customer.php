<?php
declare(strict_types=1);

namespace MagentoJapan\Zip2address\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Customer block.
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
    public function getLocale(): string
    {
        if ($this->localeResolver->getLocale() == 'ja_JP') {
            return 'ja';
        }

        return 'en';
    }
}
