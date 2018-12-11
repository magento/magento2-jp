define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/abstract',
    'mageUtils',
    'jquery',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/translate',
    'Magento_Ui/js/modal/alert'
], function (_, registry, Abstract, utils, $, fullScreenLoader, $t, alert) {
    'use strict';

    return Abstract.extend({
        validatedPostCodeExample: [],

        defaults: {
            imports: {
                update: '${ $.parentName }.country_id:value'
            }
        },

        /**
         * Callback that fires when 'value' property is updated.
         */
        onUpdate: function () {
            var postCode = this.value(),
                country = registry.get(this.parentName + '.' + 'country_id'),
                countryId = country.value(),
                patterns = window.checkoutConfig.postCodes[countryId],
                region = registry.get(this.parentName + '.' + 'region_id').uid,
                regionObj = registry.get(this.parentName + '.' + 'region_id'),
                city = registry.get(this.parentName + '.' + 'city'),
                street = registry.get(this.parentName + '.' + 'street.0'),
                endpoint = 'https://madefor.github.io/postal-code-api/api/v1',
                code1 = postCode.replace(/^([0-9]{3}).*/, "$1"),
                code2 = postCode.replace(/.*([0-9]{4})$/, "$1"),
                lang = window.checkoutConfig.zip2address.lang === 'ja_JP' ? 'ja' : 'en',
                regionSelector = $('#' + region),
                pattern,
                regex;

            this.validatedPostCodeExample = [];

            if (!utils.isEmpty(postCode) && !utils.isEmpty(patterns)) {
                for (pattern in patterns) {
                    if (patterns.hasOwnProperty(pattern)) {
                        this.validatedPostCodeExample.push(patterns[pattern]['example']);
                        regex = new RegExp(patterns[pattern]['pattern']);
                        if (regex.test(postCode) && countryId === 'JP') {
                            fullScreenLoader.startLoader();
                            $.getJSON(
                                endpoint + '/' + code1 +'/' + code2 + '.json',
                                {cache: false}
                            ).done(function (data) {
                                if (regionSelector[0]) {
                                    $(regionSelector)[0][data.data[0].prefcode].selected = true;
                                    regionObj.value($('#'+ region)[0][data.data[0].prefcode].value);
                                }

                                city.value(data.data[0][lang]['address1']);
                                street.value(data.data[0][lang]['address2']);
                            }).fail(function () {
                                alert({
                                    content: $t('Provided Zip/Postal Code seems to be invalid.')
                                });
                            }).always(function () {
                                fullScreenLoader.stopLoader();
                            });
                        }
                    }
                }
            }

            this._super();
        },

        /**
         * @param {String} value
         */
        update: function (value) {
            var country = registry.get(this.parentName + '.' + 'country_id'),
                options = country.indexedOptions,
                option;

            if (!value) {
                return;
            }

            option = options[value];

            if (option['is_zipcode_optional']) {
                this.error(false);
                this.validation = _.omit(this.validation, 'required-entry');
            } else {
                this.validation['required-entry'] = true;
            }

            this.required(!option['is_zipcode_optional']);
        }
    });
});
