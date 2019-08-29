<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Ui\View\Element;

use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Ui\Component\Form\Field;

/**
 * Make UI component address fields configuration compatible with Magento 2.3.0 and 2.3.1
 *
 * Japanese address change order of address fields in UI components XML declaration
 * However Magento 2.3.0 and 2.3.1 has different UI components for address implementation so Japanese Address
 * may reference to not declared fields. To make this module compatible with both declarations this
 * plugin removes undeclared fields.
 */
class PartiallyConfiguredUiComponent
{
    /**
     * Filter out fields without declared form element.
     *
     * @param UiComponentInterface $component
     * @param UiComponentInterface[] $children
     * @return UiComponentInterface[]
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetChildComponents(UiComponentInterface $component, array $children)
    {
        $configuredChildren = [];
        foreach ($children as $key => $child) {
            if ($child instanceof Field && null === $child->getData('config/formElement')) {
                continue;
            }
            $configuredChildren[$key] = $child;
        }
        return $configuredChildren;
    }
}
