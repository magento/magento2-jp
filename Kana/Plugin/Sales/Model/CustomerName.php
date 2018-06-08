<?php
namespace MagentoJapan\Kana\Plugin\Sales\Model;

use \Magento\Sales\Model\Order as BaseOrder;

class CustomerName
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

    public function aroundGetCustomerName(
        BaseOrder $subject,
        \Closure $proceed
    )
    {
        if($this->localeResolver->getLocale() != 'ja_JP') {
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