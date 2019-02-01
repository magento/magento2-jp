/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'postalCodeDataProvider',
    'Magento_Checkout/js/model/full-screen-loader',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'uiRegistry',
    'mage/translate',
    'underscore'
], function (postalCodeDataProvider, fullScreenLoader, validator, registry, $t, _) {
    'use strict';

    /**
     * Update address region value.
     *
     * @param {uiElement} regionSelectComponent
     * @param {uiElement} regionTextComponent
     * @param {String} newValue
     */
    var updateRegion = function (regionSelectComponent, regionTextComponent, newValue) {
            if (regionSelectComponent.visible()) {
                newValue = _.find(regionSelectComponent.options(), function (option) {
                    return option.title === newValue || option.label === newValue;
                });
                regionSelectComponent.value(newValue ? newValue.value : '');
            } else {
                regionTextComponent.value(newValue);
            }
        },

        /**
         * Update street value.
         *
         * If old value contains more extended value then new value not applied.
         *
         * @param {uiElement} component
         * @param {String} newValue
         */
        updateStreet = function (component, newValue) {
            if (component.value().indexOf(newValue) === 0) {

                return;
            }
            component.value(newValue);
        };

    return function (target) {
        var loadPostalCodeData = postalCodeDataProvider(document.documentElement.lang || 'ja');

        return target.extend({
            initialUpdateHandled: false,
            processingDelay: validator.validateDelay + 1, // schedule data load after validation processing
            scheduledHandler: null,

            /**
             * Callback that fires when 'value' property is updated.
             */
            onUpdate: function () {
                var element = this;

                element._super();

                if (!element.initialUpdateHandled) {
                    element.initialUpdateHandled = true;

                    return;
                }

                if (element.scheduledHandler) {
                    clearTimeout(element.scheduledHandler);
                }

                element.warn(null); // clean previous validation and data loading warnings
                element.scheduledHandler = setTimeout(function () {
                    if (!element.warn()) { // run only if warning now added by validation
                        element.updateRelatedAddressComponents();
                    }
                },  element.processingDelay);
            },

            /**
             * Update other address fields by provided postal code
             */
            updateRelatedAddressComponents: function () {
                var postalCodeComponent = this,
                    addressComponentName = postalCodeComponent.parentName,
                    countryComponent = registry.get(addressComponentName + '.' + 'country_id');

                if (countryComponent.value() !== 'JP') {
                    postalCodeComponent.warn(null);

                    return;
                }

                fullScreenLoader.startLoader();
                loadPostalCodeData(postalCodeComponent.value()).then(
                    function (data) {
                        var regionSelectComponent = registry.get(addressComponentName + '.' + 'region_id'),
                            regionTextComponent = registry.get(addressComponentName + '.' + 'region_id_input'),
                            cityComponent = registry.get(addressComponentName + '.' + 'city'),
                            street1Component = registry.get(addressComponentName + '.' + 'street.0'),
                            street2Component = registry.get(addressComponentName + '.' + 'street.1');

                        updateRegion(regionSelectComponent, regionTextComponent, data.region || '');
                        cityComponent.value(data.city || '');
                        updateStreet(street1Component, data.street1 || '');
                        updateStreet(street2Component, data.street2 || '');

                        postalCodeComponent.warn(null);
                        fullScreenLoader.stopLoader();
                    },
                    function (reason) {
                        postalCodeComponent.warn(
                            reason +
                            ' ' +
                            $t('If you believe it is the right one you can ignore this notice.')
                        );
                        fullScreenLoader.stopLoader();
                    }
                );
            }
        });
    };
});
