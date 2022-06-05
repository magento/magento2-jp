<?php

declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Customer\Model\Address;

use Magento\Framework\Locale\ResolverInterface;

/**
 * Class Config
 *
 * @package CommunityEngineering\JapaneseAddress\Plugin\Customer\Model\Address
 */
class Config
{
    public const JAPAN_LOCALE_SUFFIX = '_jp';

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
     * @param \Magento\Customer\Model\Address\Config $subject
     * @param string $typeCode
     *
     * @return string[]
     * @see \Magento\Customer\Model\Address\Config::getFormatByCode
     */
    public function beforeGetFormatByCode(\Magento\Customer\Model\Address\Config $subject, string $typeCode)
    {
        if ($this->localeResolver->getLocale() !== 'ja_JP') {
            return [$typeCode];
        }

        return [$typeCode . self::JAPAN_LOCALE_SUFFIX];
    }
}
