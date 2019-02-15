/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    config: {
        mixins: {
            'mage/validation': {
                'MagentoCommunity_JapaneseName/js/validation': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                'MagentoCommunity_JapaneseName/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'MagentoCommunity_JapaneseName/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/action/place-order': {
                'MagentoCommunity_JapaneseName/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/model/new-customer-address': {
                'MagentoCommunity_JapaneseName/js/address/kana-accessors': true
            },
            'Magento_Customer/js/model/customer/address': {
                'MagentoCommunity_JapaneseName/js/address/kana-accessors': true
            }
        }
    }
};
