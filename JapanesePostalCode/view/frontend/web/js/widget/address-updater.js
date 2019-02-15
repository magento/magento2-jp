/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'postalCodeDataProvider',
    'jquery',
    'mage/translate'
], function (postalCodeDataProvider, $, $t) {
    'use strict';

    /**
     * Update region value.
     *
     * @param {jQuery} regionIdElement
     * @param {jQuery} regionNameElement
     * @param {String} region
     */
    var updateRegion = function (regionIdElement, regionNameElement, region) {
            if (regionIdElement.is(':visible')) {
                regionIdElement.val(
                    regionIdElement.find('option:contains("' + region + '")').filter(function (_, option) {
                        return $(option).text() === region;
                    }).val()
                );
            } else {
                regionNameElement.val(region);
            }
        },

        /**
         * Update street element value.
         *
         * If street element contains more extended value it preserved.
         *
         * @param {jQuery} element
         * @param {String} newValue
         */
        updateStreet = function (element, newValue) {
            if (element.val().indexOf(newValue) === 0) {
                return;
            }
            element.val(newValue);
        },

        /**
         * Render message associated with element
         *
         * @param {jQuery} element
         * @param {String} message
         */
        renderAlertMessage = function (element, message) {
            var messageContainer;

            messageContainer = element.next();

            if (!messageContainer.length || !messageContainer.is('div[role="alert"]')) {
                messageContainer = $(
                    '<div role="alert" class="message warning" style="display:none">' +
                    '<span></span>' +
                    '</div>'
                );
                messageContainer.insertAfter(element);
            }

            if (!message) {
                messageContainer.hide();
            } else {
                messageContainer.children(':first').text(
                    message + ' ' + $t('If you believe it is the right one you can ignore this notice.')
                );
                messageContainer.show();
            }
        };

    $.widget('magentoJapanesePostalCode.addressUpdater', {
        /**
         * @private
         */
        _create: function () {
            var regionId = $(this.options.regionId),
                region = $(this.options.region),
                city = $(this.options.city),
                street1 = $(this.options.street1),
                street2 = $(this.options.street2),
                loadPostalCodeData = postalCodeDataProvider(this.options.lang || document.documentElement.lang || 'ja');

            $(this.element).on('change', function (event) {
                loadPostalCodeData(event.target.value).then(
                    function (data) {
                        updateRegion(regionId, region, data.region);
                        region.val(data.region);
                        city.val(data.city);
                        updateStreet(street1, data.street1);
                        updateStreet(street2, data.street2);

                        renderAlertMessage($(event.target));
                    },
                    function (reason) {
                        renderAlertMessage($(event.target), reason);
                    }
                );
            });
        }
    });

    return $.magentoJapanesePostalCode.addressUpdater;
});
