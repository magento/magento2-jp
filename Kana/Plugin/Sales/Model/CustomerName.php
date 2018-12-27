<?php
declare(strict_types=1);

namespace MagentoJapan\Kana\Plugin\Sales\Model;

use \Magento\Sales\Model\Order as BaseOrder;

/**
 * Modify order customer name according to JP locale requirements.
 */
class CustomerName
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
     * Modify order customer name according to JP locale requirements.
     *
     * @param BaseOrder $subject
     * @param \Closure $proceed
     * @return mixed|string
     */
    public function aroundGetCustomerName(
        BaseOrder $subject,
        \Closure $proceed
    ) {
        if ($this->localeResolver->getLocale() != 'ja_JP') {
            return $proceed();
        } else {
            if ($subject->getCustomerFirstname()) {
                $customerName = $subject->getCustomerLastname() . ' ' . $subject->getCustomerFirstname();
            } else {
                $customerName = (string)__('Guest');
            }
            return $customerName;
        }
    }
}
