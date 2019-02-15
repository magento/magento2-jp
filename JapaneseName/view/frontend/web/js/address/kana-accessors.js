/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'mage/utils/wrapper'
], function (_, wrapper) {
    'use strict';

    var readCustomAttributes,
        getCustomAttribute,
        getFirstnamekana,
        getLastnamekana,
        getNamekana;

    /**
     * Read DTO custom attributes into simple object.
     *
     * @param {Object} object
     * @return {Object}
     */
    readCustomAttributes = function (object) {
        var data = {},
            index;

        if (typeof object.customAttributes === 'undefined') {
            return data;
        }

        for (index in object.customAttributes) {
            if (object.customAttributes.hasOwnProperty(index)) {
                data[object.customAttributes[index]['attribute_code']] = object.customAttributes[index].value;
            }
        }

        return data;
    };

    /**
     * Read custom attribute value by code.
     *
     * @param {String} code
     * @returns {String|null}
     */
    getCustomAttribute = function (code) {
        var customAttributes = readCustomAttributes(this);

        if (customAttributes.hasOwnProperty(code)) {
            return customAttributes[code];
        }

        return null;
    };

    /**
     * Get first name kana.
     *
     * @returns {String|null}
     */
    getFirstnamekana = function () {
        return this.getCustomAttribute('firstnamekana');
    };

    /**
     * Get last name kana.
     *
     * @returns {String|null}
     */
    getLastnamekana = function () {
        return this.getCustomAttribute('lastnamekana');
    };

    /**
     * Get full name kana.
     *
     * @returns {String}
     */
    getNamekana = function () {
        return [this.getLastnamekana(), this.getFirstnamekana()].
            filter(function (v) {
                return v !== null;
            }).
            join(' ');
    };

    return function (addressFactory) {
        return wrapper.wrap(addressFactory, function (originalFactory) {
            var address = originalFactory();

            address.getCustomAttribute = getCustomAttribute;
            address.getFirstnamekana = getFirstnamekana;
            address.getLastnamekana = getLastnamekana;
            address.getNamekana = getNamekana;

            return address;
        });
    };
});
