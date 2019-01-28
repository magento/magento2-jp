define([
    'jquery'
], function ($) {
    'use strict';

    return {
        apiEndpoint: 'https://madefor.github.io/postal-code-api/api/v1',
        noDashPostcodeMask: new RegExp(/^[0-9]{7}$/),
        hasDashPostcodeMask: new RegExp(/^[0-9]{3}\-?[0-9]{4}$/),

        /**
         * Convert JP postcode into JP address using 3rd-party service.
         *
         * @param {String} postcode
         * @return {*}
         */
        convert: function (postcode) {
            var firstPart, secondPart;

            if (!(this.noDashPostcodeMask.test(postcode) || this.hasDashPostcodeMask.test(postcode))) {
                return;
            }

            firstPart = postcode.replace(/^([0-9]{3}).*/, '$1');
            secondPart = postcode.replace(/.*([0-9]{4})$/, '$1');

            return $.getJSON(
                this.apiEndpoint + '/' + firstPart + '/' + secondPart + '.json',
                {
                    cache: false
                }
            );
        }
    };
});
