define([
    'jquery',
    'mage/utils/wrapper',
    'mageUtils',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/translate',
    'Magento_Ui/js/modal/alert'
], function ($, wrapper, utils, fullScreenLoader, $t) {
    'use strict';
    return function(validate){
        return wrapper.wrap(validate, {
            validatedPostCodeExample: [],
            validate: function(postCode, countryId) {
                var patterns = window.checkoutConfig.postCodes[countryId],
                    lang = window.checkoutConfig.zip2address.lang === 'ja_JP' ? 'ja' : 'en',
                    endpoint = 'https://madefor.github.io/postal-code-api/api/v1',
                    code1 = postCode.replace(/^([0-9]{3}).*/, "$1"),
                    code2 = postCode.replace(/.*([0-9]{4})$/, "$1"),
                    regex,
                    pattern;

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
                                    $('select[name="region_id"]')[0][data.data[0].prefcode].selected = true;
                                    $('select[name="region_id"]').trigger('change');
                                    $('input[name="city"]').val(data.data[0][lang]['address1']).trigger('change');
                                    $('input[name="street[0]"]').val(data.data[0][lang]['address2']).trigger('change');

                                }).fail(function () {
                                    alert({
                                        content: $t('Provided Zip/Postal Code seems to be invalid.')
                                    });
                                }).always(function () {
                                    fullScreenLoader.stopLoader();
                                });

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
