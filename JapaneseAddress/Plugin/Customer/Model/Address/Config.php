<?php

declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Customer\Model\Address;

use Magento\Framework\Locale\ResolverInterface;

use function _PHPStan_c862bb974\RingCentral\Psr7\str;

/**
 * Class Config
 *
 * @package CommunityEngineering\JapaneseAddress\Plugin\Customer\Model\Address
 */
class Config
{
    public const JAPAN_LOCALE_SUFFIX = '_jp';
    public const OTHER_LOCALE_SUFFIX = '_other';

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
        // In case of order address template that have specified store locale then remove _other suffix and return.
        if (str_contains($typeCode, self::OTHER_LOCALE_SUFFIX)) {
            return str_replace(self::OTHER_LOCALE_SUFFIX, '', $typeCode);
        }
        if ($this->localeResolver->getLocale() !== 'ja_JP' || str_contains($typeCode, self::JAPAN_LOCALE_SUFFIX)) {
            return [$typeCode];
        }

        return [$typeCode . self::JAPAN_LOCALE_SUFFIX];
    }
}
