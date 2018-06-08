<?php
namespace MagentoJapan\Kana\Plugin\Sales\Model\Order;

use \Magento\Sales\Model\Order\Address as BaseAddress;

class AddressName
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

    public function aroundGetName(
        BaseAddress $subject,
        \Closure $proceed
    )
    {
        if($this->localeResolver->getLocale() != 'ja_JP') {
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