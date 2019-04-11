<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Customer\Block\Form;

use Magento\Customer\Block\Form\Register;
use Magento\Framework\DataObject;

/**
 * If customer decided to create account after order placement address data should be filled from quote data.
 */
class RegisterWithQuoteAddress
{
    /**
     * Copy address data to top level data transfer object to make them available in form
     *
     * @param Register $form
     * @param mixed $data
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetFormData(Register $form, $data)
    {
        if (!$data instanceof DataObject) {
            return $data;
        }

        if ($data->getData('address_data_imported')) {
            return $data;
        }

        $data->setData('address_data_imported', true);

        $addresses = $data->getData('addresses');
        if (!isset($addresses[0])) {
            return $data;
        }

        foreach ($addresses[0] as $key => $value) {
            if (!$data->hasData($key)) {
                if ($key === 'region' && is_array($value)) {
                    $data->setData($key, $value['region']);
                } else {
                    $data->setData($key, $value);
                }
            }
        }
        return $data;
    }
}
