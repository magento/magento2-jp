<?php
declare(strict_types=1);

namespace MagentoJapan\Kana\Plugin\Sales\Model\Order;

use \Magento\Sales\Model\Order\Address as BaseAddress;

/**
 * Modify Order address Customer name according to JP locale requirements.
 */
class AddressName
{
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $localeResolver;

    /**
     * @var \Magento\Eav\Model\Config
     */
    private $config;

    /**
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     * @param \Magento\Eav\Model\Config $config
     */
    public function __construct(
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magento\Eav\Model\Config $config
    ) {
        $this->localeResolver = $localeResolver;
        $this->config = $config;
    }

    /**
     * Modify Order address Customer name according to JP locale requirements.
     *
     * @param BaseAddress $subject
     * @param \Closure $proceed
     * @return mixed|string
     */
    public function aroundGetName(
        BaseAddress $subject,
        \Closure $proceed
    ) {
        if ($this->localeResolver->getLocale() != 'ja_JP') {
            return $proceed();
        } else {
            $name = '';
            if ($subject->getPrefix()) {
                $name .= $subject->getPrefix() . ' ';
            }
            $name .= $subject->getLastname();
            if ($subject->getMiddlename()) {
                $name .= ' ' . $subject->getMiddlename();
            }
            $name .= ' ' . $subject->getFirstname();
            if ($subject->getSuffix()) {
                $name .= ' ' . $subject->getSuffix();
            }
            return $name;
        }
    }
}
