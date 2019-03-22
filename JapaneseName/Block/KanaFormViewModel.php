<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Block;

use Magento\Framework\Api\AttributeInterface;
use Magento\Framework\Api\CustomAttributesDataInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use CommunityEngineering\JapaneseName\Model\Config\KanaFieldsConfig;
use CommunityEngineering\JapaneseName\Model\Config\Source\Kana;

/**
 * View model of kana fields representation at account management pages.
 */
class KanaFormViewModel implements ArgumentInterface
{
    /**
     * @var KanaFieldsConfig
     */
    private $kanaFieldsConfig;

    /**
     * @param KanaFieldsConfig $kanaFieldsConfig
     */
    public function __construct(
        KanaFieldsConfig $kanaFieldsConfig
    ) {
        $this->kanaFieldsConfig = $kanaFieldsConfig;
    }

    /**
     * Should customer see kana fields?
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->kanaFieldsConfig->areEnabled();
    }

    /**
     * Are kana fields required to register customer and address?
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->kanaFieldsConfig->areRequired();
    }

    /**
     * Get validation rules for kana according to admin configuration.
     *
     * @return string
     */
    public function getValidationRules(): string
    {
        $rules = [];
        if ($this->kanaFieldsConfig->areRequired()) {
            $rules[] = 'required';
        }
        switch ($this->kanaFieldsConfig->getKanaType()) {
            case Kana::TYPE_ANY:
                $rules[] = 'validate-kana';
                break;
            case Kana::TYPE_KATAKANA:
                $rules[] = 'validate-katakana';
                break;
            case Kana::TYPE_HIRAGANA:
                $rules[] = 'validate-hiragana';
                break;
        }

        $rulesAttr = join(',', array_map(
            function ($rule) {
                return sprintf('"%s":true', $rule);
            },
            $rules
        ));

        return $rulesAttr;
    }

    /**
     * Read kana field values from data object.
     *
     * In most cases object would be instance of \Magento\Customer\Api\Data\CustomerInterface.
     * Possibility to pass any value implemented for full compatibility with current legacy implementation in
     * Magento core modules.
     *
     * @param mixed $customer
     * @param string $kanaAttributeCode
     * @return string
     */
    public function extractKanaValue($customer, string $kanaAttributeCode): string
    {
        if ($customer instanceof CustomAttributesDataInterface) {
            $customAttribute = $customer->getCustomAttribute($kanaAttributeCode);
            if ($customAttribute instanceof AttributeInterface) {
                return (string)$customAttribute->getValue();
            }
            return (string)$customAttribute;
        }

        $getter = 'get' . join('', array_map(
            'ucfirst',
            explode('_', $kanaAttributeCode)
        ));
        if (is_callable([$customer, $getter])) {
            return (string)$customer->{$getter}();
        }

        return '';
    }
}
