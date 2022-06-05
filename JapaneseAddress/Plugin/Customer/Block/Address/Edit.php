<?php
namespace CommunityEngineering\JapaneseAddress\Plugin\Customer\Block\Address;

use CommunityEngineering\JapaneseAddress\Model\Config\CountryInputConfig;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Class Edit
 *
 * @package CommunityEngineering\JapaneseAddress\Plugin\Customer\Block\Address
 */
class Edit
{
    /**
     * @var ResolverInterface
     */
    protected $localeResolver;

    /**
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     */
    public function __construct(
        ResolverInterface $localeResolver
    ) {
        $this->localeResolver = $localeResolver;
    }

    /**
     * @param \Magento\Customer\Block\Address\Edit $subject
     * @param string $template
     *
     * @return string[]
     */
    public function beforeSetTemplate(\Magento\Customer\Block\Address\Edit $subject, string $template)
    {
        if ($this->localeResolver->getLocale() !== 'ja_JP') {
            return [$template];
        }
        if ($template === 'Magento_Customer::address/edit.phtml') {
            return ['CommunityEngineering_JapaneseAddress::address/edit.phtml'];
        }
        return [$template];
    }
}
