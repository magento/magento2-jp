<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseName\Plugin\Quote\Model\Quote;

use Magento\Customer\Api\Data\AddressInterface as CustomerAddress;
use Magento\Quote\Model\Quote\Address as QuoteAddress;

/**
 * Custom attributes are not supported by fieldset copy mechanism so fields should be copied manually.
 */
class ExportAddressKana
{
    /**
     * Copy kana extension attributes from quote to customer address custom attributes.
     *
     * @param QuoteAddress $from
     * @param CustomerAddress $to
     * @return CustomerAddress
     */
    public function afterExportCustomerAddress(QuoteAddress $from, CustomerAddress $to)
    {
        $addressExtension = $from->getExtensionAttributes();
        if ($addressExtension === null) {
            return $to;
        }

        $to->setCustomAttribute('firstnamekana', $addressExtension->getFirstnamekana());
        $to->setCustomAttribute('lastnamekana', $addressExtension->getLastnamekana());

        return $to;
    }
}
