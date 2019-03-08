/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    config: {
        mixins: {
            'mage/validation': {
                'Magento_JapaneseName/js/validation': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                'Magento_JapaneseName/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'Magento_JapaneseName/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Magento_JapaneseName/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/model/new-customer-address': {
                'Magento_JapaneseName/js/address/kana-accessors': true
            },
            'Magento_Customer/js/model/customer/address': {
                'Magento_JapaneseName/js/address/kana-accessors': true
            }
        }
    }
};
