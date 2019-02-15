/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'Magento_Checkout/js/model/quote',
    'jquery',
    'underscore',
    'mage/utils/wrapper'
], function (quote, $, _, wrapper) {
    'use strict';

    /**
     * Copy kana attribute from custom to extensions attributes.
     *
     * @param {Object} address
     * @param {String} attr
     */
    var addKanaExtensionAttribute = function (address, attr) {
        var attrCode = address.customAttributes[attr]['attribute_code'];

        if (attrCode === 'firstnamekana' || attrCode === 'lastnamekana') {
            address['extension_attributes'][attrCode] = address.customAttributes[attr].value;
        }
    };

    /**
     * Kana fields added as custom attributes based on customer address declaration.
     * To persist kana in quote and order addresses they data should be transferred to extension attributes.
     */
    return function (setAddressInformationAction) {
        return wrapper.wrap(setAddressInformationAction, function (originalAction) {
            _.each([quote.shippingAddress(), quote.billingAddress()], function (address) {
                var attr;

                if (typeof address === 'undefined' || address === null) {
                    return;
                }

                if (address['extension_attributes'] === undefined) {
                    address['extension_attributes'] = {};
                }

                for (attr in  address.customAttributes) {
                    if (address.customAttributes.hasOwnProperty(attr)) {
                        addKanaExtensionAttribute(address, attr);
                    }
                }
            });

            return originalAction();
        });
    };
});
