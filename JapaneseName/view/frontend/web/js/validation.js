/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Register kana validators.
 */
define([
    'Magento_Ui/js/lib/validation/validator',
    'jquery',
    'underscore',
    'mage/translate'
], function (validator, $, _) {
    'use strict';

    var rules = [
        {
            id: 'validate-hiragana',

            /**
             * Verify value contains only Hiragana characters.
             *
             * @param {String} value
             * @returns {Boolean}
             */
            handler: function (value) {
                return /^([\u3040-\u309F|\u30FB-\u30FC])*$/.test(value);
            },
            errorMessage: $.mage.__('Please use Hiragana only in this field.')
        },
        {
            id: 'validate-katakana',

            /**
             * Verify value contains only Katakana characters.
             *
             * @param {String} value
             * @returns {Boolean}
             */
            handler: function (value) {
                return /^([\u30A1-\u30FC])*$/.test(value);
            },
            errorMessage: $.mage.__('Please use full width Katakana only in this field.')
        },
        {
            id: 'validate-kana',

            /**
             * Verify value contains only Kana characters.
             *
             * @param {String} value
             * @returns {Boolean}
             */
            handler: function (value) {
                return /^([\u3040-\u309F|\u30FB-\u30FC|\u30A1-\u30FC])*$/.test(value);
            },
            errorMessage: $.mage.__('Please use full width kana only in this field.')
        }
    ];

    return function (target) {
        _.each(rules, function (rule) {
            validator.addRule(
                rule.id,
                rule.handler,
                $.mage.__(rule.errorMessage)
            );
            $.validator.addMethod(
                rule.id,
                rule.handler,
                $.mage.__(rule.errorMessage)
            );
        });

        return target;
    };
});
