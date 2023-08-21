/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/translate'
], function ($, $t) {
    'use strict';

    var apiEndpoint = 'https://madefor.github.io/postal-code-api/api/v1',

        /**
         * Parse Japan postal Code to detect parts.
         *
         * @param {String} postalCode
         * @return {{firstPart: (String), secondPart: (String)}|null}
         */
        parsePostalCode = function (postalCode) {
            var matches = postalCode.match(/^([0-9]{3})-?([0-9]{4})$/);

            if (matches === null) {
                return null;
            }

            return {
                firstPart: matches[1],
                secondPart: matches[2]
            };
        },

        /**
         * Build URL to fetch data for postal code.
         *
         * @param {{firstPart: (String), secondPart: (String)}} postalCode
         * @return {String}
         */
        buildApiUrl = function (postalCode) {
            return apiEndpoint + '/' + postalCode.firstPart + '/' + postalCode.secondPart + '.json';
        },

        /**
         * Call remote service to fetch data for postal code.
         *
         * @param {{firstPart: (String), secondPart: (String)}} postalCode
         * @return {Promise}
         */
        callApi = function (postalCode) {
            var apiUrl = buildApiUrl(postalCode);

            return $.getJSON(apiUrl);
        },

        /**
         * Map remote service response format to Magento data structure.
         *
         * @param {Object} serviceData
         * @return {{region: (String|null), city: (String|null), street1: (String|null), street2: (String|null)}}
         */
        mapServiceData = function (serviceData) {
            return {
                region: serviceData.prefecture || null,
                city: serviceData.address1 || null,
                street1: [serviceData.address2 || null, serviceData.address3 || null].join('') || null,
                street2: serviceData.address4 || null
            };
        },

        /**
         * Get postal code data in specified language.
         *
         * If data is not available in requested language then Japanese is used.
         *
         * @param {String} postalCode
         * @param {String} lang
         * @return {Promise}
         */
        getPostalCodeData = function (postalCode, lang)  {
            var parsedPostalCode,
                result = $.Deferred();

            parsedPostalCode = parsePostalCode(postalCode);

            if (parsedPostalCode === null) {
                result.reject(
                    $t('Provided Zip/Postal Code seems to be invalid.') +
                    $t(' Example: ') + '123-4567; 1234567.'
                );

                return result.promise();
            }

            callApi(parsedPostalCode).done(function (apiResponse) {
                var data;

                if (typeof apiResponse.data === 'undefined' || typeof apiResponse.data[0] === 'undefined') {
                    result.reject(/* implementation should be updated. Do not show to customer. */);

                    return;
                }

                data = apiResponse.data[0][lang] || apiResponse.data[0].ja;

                if (typeof data === 'undefined') {
                    result.reject(/* implementation should be updated. Do not show to customer. */);

                    return;
                }

                result.resolve(mapServiceData(data));
            }).fail(function (apiResponse) {
                if (apiResponse.status === 404) {
                    result.reject($t('Provided Zip/Postal Code is unknown.'));
                } else {
                    result.reject($t('Unable to verify provided Zip/Postal Code.'));
                }
            });

            return result.promise();
        };

    return function (lang) {
        return function (postalCode) {
            return getPostalCodeData(postalCode, lang);
        };
    };
});
