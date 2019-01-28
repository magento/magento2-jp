/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    config: {
        mixins: {
            'mage/validation': {
                'MagentoJapan_Kana/js/validation': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                'MagentoJapan_Kana/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'MagentoJapan_Kana/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/action/place-order': {
                'MagentoJapan_Kana/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/model/new-customer-address': {
                'MagentoJapan_Kana/js/address/kana-accessors': true
            },
            'Magento_Customer/js/model/customer/address': {
                'MagentoJapan_Kana/js/address/kana-accessors': true
            }
        }
    }
};
