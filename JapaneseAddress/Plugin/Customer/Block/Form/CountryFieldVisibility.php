<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Customer\Block\Form;

use Magento\Framework\View\Element\AbstractBlock;
use CommunityEngineering\JapaneseAddress\Model\Config\CountryInputConfig;

/**
 * Modify address form to show or hide country field.
 */
class CountryFieldVisibility
{
    /**
     * @var CountryInputConfig
     */
    private $countryFieldConfig;

    /**
     * @param CountryInputConfig $countryFieldConfig
     */
    public function __construct(CountryInputConfig $countryFieldConfig)
    {
        $this->countryFieldConfig = $countryFieldConfig;
    }

    /**
     * Modify HTML with country field after block rendering.
     *
     * @param AbstractBlock $block
     * @param string $html
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterToHtml(AbstractBlock $block, $html)
    {
        if ($this->countryFieldConfig->isVisibleAtStorefront()) {
            return $html;
        }

        $hidingCssHack = 'style="display:none;visibility:hidden;height:0px;padding:0px;margin:0px;"';
        $modifiedHtml = str_replace(
            'class="field country required"',
            'class="field country required" ' . $hidingCssHack,
            $html
        );

        return $modifiedHtml;
    }
}
