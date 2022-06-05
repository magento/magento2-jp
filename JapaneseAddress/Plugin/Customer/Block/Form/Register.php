<?php

namespace CommunityEngineering\JapaneseAddress\Plugin\Customer\Block\Form;

use Magento\Framework\Locale\ResolverInterface;

/**
 * Class Register
 *
 * @package CommunityEngineering\JapaneseAddress\Plugin\Customer\Block\Form
 */
class Register
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
     * @param \Magento\Customer\Block\Form\Register $subject
     * @param string $template
     *
     * @return string[]
     */
    public function beforeSetTemplate(\Magento\Customer\Block\Form\Register $subject, string $template)
    {
        if ($this->localeResolver->getLocale() !== 'ja_JP') {
            return [$template];
        }

        if ($template === 'Magento_Customer::form/register.phtml') {
            return ['CommunityEngineering_JapaneseAddress::account/register.phtml'];
        }
        return [$template];
    }
}
