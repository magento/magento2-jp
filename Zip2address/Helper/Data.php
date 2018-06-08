<?php
namespace MagentoJapan\Zip2address\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $localeResolver;


    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Locale\ResolverInterface $localeResolver
    ) {
        parent::__construct($context);
        $this->localeResolver = $localeResolver;
    }

    /**
     * return current locale code
     */
    public function getCurrentLocale()
    {
        if($this->localeResolver->getLocale() == 'ja_JP')
        {
            return 'ja';
        }

        return 'en';
    }
}