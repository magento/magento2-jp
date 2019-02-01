/**
* Copyright © Magento, Inc. All rights reserved.
* See COPYING.txt for license details.
*/

define([
    'Magento_Checkout/js/model/postcode-validator',
    'uiRegistry',
    'mage/translate'
], function (postcodeValidator, registry, $t) {
    'use strict';

    return function (target) {

        /**
         * Validate postcode value.
         *
         * Default Magento implementation ready country id only form visible field
         * but in most cases country should by hidden from customer.
         * Implementation of postcodeValidation method should be copy of core implementation
         * except countryId value detection.
         * This fix should be migrated to the Magento_Checkout module eventually.
         *
         * @param {uiElement} postcodeElement
         * @return {Boolean}
         */
        target.postcodeValidation = function (postcodeElement) {
            var countryElement,
                validationResult,
                warnMessage;

            if (postcodeElement == null || postcodeElement.value() == null) {
                return true;
            }

            postcodeElement.warn(null);

            countryElement = registry.get(postcodeElement.parentName + '.' + 'country_id');
            validationResult = postcodeValidator.validate(postcodeElement.value(), countryElement.value());

            if (!validationResult) {
                warnMessage = $t('Provided Zip/Postal Code seems to be invalid.');

                if (postcodeValidator.validatedPostCodeExample.length) {
                    warnMessage += $t(' Example: ') + postcodeValidator.validatedPostCodeExample.join('; ') + '. ';
                }
                warnMessage += $t('If you believe it is the right one you can ignore this notice.');
                postcodeElement.warn(warnMessage);
            }

            return validationResult;
        };

        return target;
    };
});
