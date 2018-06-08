define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/abstract',
    'mageUtils',
    'jquery',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/translate'
], function (_, registry, Abstract, utils, $, fullScreenLoader, $t) {
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
            this._super();
            var postCode = this.value();
            var country = registry.get(this.parentName + '.' + 'country_id');
            var countryId = country.value();
            var patterns = window.checkoutConfig.postCodes[countryId];
            var region = registry.get(this.parentName + '.' + 'region_id').uid;
            var regionObj = registry.get(this.parentName + '.' + 'region_id');
            var city = registry.get(this.parentName + '.' + 'city');
            var street = registry.get(this.parentName + '.' + 'street.0');

            this.validatedPostCodeExample = [];

            if (!utils.isEmpty(postCode) && !utils.isEmpty(patterns)) {
                for (var pattern in patterns) {
                    if (patterns.hasOwnProperty(pattern)) {
                        this.validatedPostCodeExample.push(patterns[pattern]['example']);
                        var lang = window.checkoutConfig.zip2address.lang;
                        var regex = new RegExp(patterns[pattern]['pattern']);
                        if (regex.test(postCode)) {

                            if(countryId == 'JP') {
                                fullScreenLoader.startLoader();
                                var endpoint = 'https://madefor.github.io/postal-code-api/api/v1';
                                var code1 = postCode.replace(/^([0-9]{3}).*/, "$1");
                                var code2 = postCode.replace(/.*([0-9]{4})$/, "$1");

                                $.ajax({
                                    type: 'GET',
                                    dataType : 'json',
                                    url: endpoint + '/' + code1 +'/' + code2 + '.json',
                                    cache: false,

                                    success: function (json) {
                                        var data = eval(json);

                                        if($('#'+ region)[0]) {
                                            $('#'+ region)[0][data.data[0].prefcode].selected = true;
                                            regionObj.value($('#'+ region)[0][data.data[0].prefcode].value);
                                        }

                                        city.value(data.data[0][lang]['address1']);
                                        street.value(data.data[0][lang]['address2']);

                                    },
                                    error: function (json) {
                                        alert($t('Provided Zip/Postal Code seems to be invalid.'));
                                    }
                                });
                                fullScreenLoader.stopLoader();
                            }
                        }
                    }
                }
            }

        },

        /**
         * @param {String} value
         */
        update: function (value) {
            var country = registry.get(this.parentName + '.' + 'country_id'),
                options = country.indexedOptions,
                option;
            var postcode = registry.get(this.parentName + '.' + 'postcode');

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
