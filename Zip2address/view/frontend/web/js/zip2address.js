define([
    'jquery',
    'mage/template',
    'mage/translate',
    'jquery/ui',
    'mage/validation'
], function ($, mageTemplate, $t) {
    'use strict';
    var self;

    $.widget('vw.zip2address', {
        _create: function () {
            self = $;
            $(this.element).on('change', $.proxy(this._search, this));

        },

        _search: function(event) {
            self = $;
            var postcode = event.target.value;
            var noDash = new RegExp(/^[0-9]{7}$/);
            var hasDash = new RegExp(/^[0-9]{3}\-?[0-9]{4}$/);
            if(noDash.test(postcode) || hasDash.test(postcode)) {
                var endpoint = 'https://madefor.github.io/postal-code-api/api/v1';
                var code1 = postcode.replace(/^([0-9]{3}).*/, "$1");
                var code2 = postcode.replace(/.*([0-9]{4})$/, "$1");
                var region = this.options.region;
                var city = this.options.city;
                var street = this.options.street;
                var lang = this.options.lang;

                $.ajax({
                    type: 'GET',
                    dataType : 'json',
                    url: endpoint + '/' + code1 +'/' + code2 + '.json',
                    cache: false,

                    success: function (json) {
                        var data = eval(json);


                        self(region)[0][data.data[0].prefcode].selected = true;
                        self(city).val(data.data[0][lang]['address1']);
                        self(street).val(data.data[0][lang]['address2']);

                    },
                    error: function (json) {
                        alert($t('Provided Zip/Postal Code seems to be invalid.'));
                    }
                });
            }
        }

    });

    return $.vw.zip2address;
});
