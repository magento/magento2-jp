/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    config: {
        mixins: {
            'mage/validation': {
                'CommunityEngineering_JapaneseName/js/validation': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                'CommunityEngineering_JapaneseName/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'CommunityEngineering_JapaneseName/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/action/place-order': {
                'CommunityEngineering_JapaneseName/js/checkout/action/set-addresses-information': true
            },
            'Magento_Checkout/js/model/new-customer-address': {
                'CommunityEngineering_JapaneseName/js/address/kana-accessors': true
            },
            'Magento_Customer/js/model/customer/address': {
                'CommunityEngineering_JapaneseName/js/address/kana-accessors': true
            }
        }
    }
};
