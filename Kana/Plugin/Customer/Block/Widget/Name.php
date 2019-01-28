<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace MagentoJapan\Kana\Plugin\Customer\Block\Widget;

use Magento\Customer\Block\Widget\Name as Subject;

/**
 * Use name widget that corresponds to Japanese traditions and contains kana fields.
 */
class Name
{
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
        if ($template === 'Magento_Customer::widget/name.phtml') {
            return ['MagentoJapan_Kana::customer/widget/name.phtml'];
        }
        return [$template];
    }
}
