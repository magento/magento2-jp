<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Plugin\Customer\Model\Delegation;

use Magento\Customer\Model\Delegation\Data\NewOperation;
use Magento\Customer\Model\Delegation\Storage;
use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\Api\CustomAttributesDataInterface;

/**
 * Customer data for registration after order placement are stored in flat array.
 *
 * To correctly restore data kana fields should be moved from main data object to custom attributes.
 */
class NewOperationNormalization
{
    /**
     * Put kana data in correct place after object restore.
     *
     * @param Storage $subject
     * @param NewOperation|null $result
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterConsumeNewOperation(Storage $subject, $result)
    {
        if (!$result instanceof NewOperation) {
            return $result;
        }

        $customer = $result->getCustomer();

        $this->normalizeDataObjects($customer);
        foreach ($customer->getAddresses() as $address) {
            $this->normalizeDataObjects($address);
        }

        return $result;
    }

    /**
     * If kana data present then they should be placed in custom attributes.
     *
     * @param mixed $dto
     */
    private function normalizeDataObjects($dto): void
    {
        if (!$dto instanceof AbstractSimpleObject || !$dto instanceof CustomAttributesDataInterface) {
            return;
        }

        $data = $dto->__toArray();
        foreach (['firstnamekana', 'lastnamekana'] as $kanaAttribute) {
            if (array_key_exists($kanaAttribute, $data)) {
                $dto->setCustomAttribute($kanaAttribute, $data[$kanaAttribute]);
            }
        }
    }
}
