<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Plugin\Customer\Block\Widget;

use Magento\Customer\Block\Widget\Name as Subject;
use CommunityEngineering\JapaneseName\Model\Config\KanaFieldsConfig;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Use name widget that corresponds to Japanese traditions and contains kana fields.
 */
class Name
{
    /**
     * @var KanaFieldsConfig
     */
    private $kanaFieldsConfig;

    /**
     * @var ResolverInterface
     */
    protected $localeResolver;

    /**
     * @param \CommunityEngineering\JapaneseName\Model\Config\KanaFieldsConfig $kanaFieldsConfig
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     */
    public function __construct(
        KanaFieldsConfig $kanaFieldsConfig,
        ResolverInterface $localeResolver
    ) {
        $this->kanaFieldsConfig = $kanaFieldsConfig;
        $this->localeResolver = $localeResolver;
    }

    /**
     * Substitute default template.
     *
     * @param Subject $subject
     * @param string $template
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSetTemplate(Subject $subject, string $template)
    {
        if ($this->localeResolver->getLocale() !== 'ja_JP') {
            return [$template];
        }

        if ($template === 'Magento_Customer::widget/name.phtml' && $this->kanaFieldsConfig->areEnabled()) {
            return ['CommunityEngineering_JapaneseName::customer/widget/name.phtml'];
        }
        return [$template];
    }
}
