define([
    'jquery',
    'mage/translate',
    'Magento_Ui/js/modal/alert'
], function ($, $t, alert) {
    'use strict';

    $.widget('vw.zip2address', {
        /**
         * @private
         */
        _create: function () {
            $(this.element).on('change', $.proxy(this._search, this));
        },

        /**
         * @inheritDoc
         */
        _search: function (event) {
            var postcode = event.target.value,
                noDash = new RegExp(/^[0-9]{7}$/),
                hasDash = new RegExp(/^[0-9]{3}\-?[0-9]{4}$/),
                endpoint = 'https://madefor.github.io/postal-code-api/api/v1',
                region = this.options.region,
                city = this.options.city,
                street = this.options.street,
                lang = this.options.lang === 'ja_JP' ? 'ja' : 'en',
                code1,
                code2;

            if (noDash.test(postcode) || hasDash.test(postcode)) {
                code1 = postcode.replace(/^([0-9]{3}).*/, '$1');
                code2 = postcode.replace(/.*([0-9]{4})$/, '$1');

                $.getJSON(
                    endpoint + '/' + code1 + '/' + code2 + '.json',
                    {
                        cache: false
                    }
                ).done(function (data) {
                    $(region)[0][data.data[0].prefcode].selected = true;
                    $(city).val(data.data[0][lang].address1);
                    $(street).val(data.data[0][lang].address2);
                }).fail(function () {
                    alert({
                        content: $t('Provided Zip/Postal Code seems to be invalid.')
                    });
                });
            }
        }
    });

    return $.vw.zip2address;
});
