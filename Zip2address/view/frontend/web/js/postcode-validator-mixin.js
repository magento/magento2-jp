define([
    'jquery',
    'mage/utils/wrapper',
    'mageUtils',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/translate'
], function ($, wrapper, utils, fullScreenLoader, $t) {
    'use strict';
    return function(validate){
        return wrapper.wrap(validate, {
            validatedPostCodeExample: [],
            validate: function(postCode, countryId) {
                var patterns = window.checkoutConfig.postCodes[countryId];
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
                                            $('select[name="region_id"]')[0][data.data[0].prefcode].selected = true;
                                            $('select[name="region_id"]').trigger('change');
                                            $('input[name="city"]').val(data.data[0][lang]['address1']).trigger('change');
                                            $('input[name="street[0]"]').val(data.data[0][lang]['address2']).trigger('change');
                                        },
                                        error: function (json) {
                                            alert($t('Provided Zip/Postal Code seems to be invalid.'));
                                        }
                                    });
                                    fullScreenLoader.stopLoader();
                                }

                                return true;
                            }
                        }
                    }
                    return false;
                }
                return true;
            }
        });
    };
});
