<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Plugin\Customer\Block\Widget;

use Magento\Customer\Block\Widget\Name as Subject;
use CommunityEngineering\JapaneseName\Model\Config\KanaFieldsConfig;

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
     * Name constructor.
     * @param KanaFieldsConfig $kanaFieldsConfig
     */
    public function __construct(
        KanaFieldsConfig $kanaFieldsConfig
    ) {
        $this->kanaFieldsConfig = $kanaFieldsConfig;
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
        if ($template === 'Magento_Customer::widget/name.phtml' && $this->kanaFieldsConfig->areEnabled()) {
            return ['CommunityEngineering_JapaneseName::customer/widget/name.phtml'];
        }
        return [$template];
    }
}
