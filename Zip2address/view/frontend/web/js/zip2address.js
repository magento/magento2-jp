define([
    'jquery',
    'underscore',
    'mage/translate',
    'MagentoJapan_Zip2address/js/action/zip2address',
    'Magento_Ui/js/modal/alert'
], function ($, _, $t, Zip2address, alert) {
    'use strict';

    $.widget('vw.zip2address', {
        /**
         * @private
         */
        _create: function () {
            $(this.element).on('change', $.proxy(this._search, this));
        },

        /**
         * @override
         */
        _search: function (event) {
            var postcode = event.target.value,
                region = this.options.region,
                city = this.options.city,
                street = this.options.street,
                lang = this.options.lang === 'ja_JP' ? 'ja' : 'en',
                convert = Zip2address.convert(postcode);

            if (_.isUndefined(convert)) {
                return;
            }

            convert.success(function (data) {
                $(region)[0][data.data[0].prefcode].selected = true;
                $(city).val(data.data[0][lang].address1);
                $(street).val(data.data[0][lang].address2);
            }).fail(function () {
                alert({
                    content: $t('Provided Zip/Postal Code seems to be invalid.')
                });
            });
        }
    });

    return $.vw.zip2address;
});
