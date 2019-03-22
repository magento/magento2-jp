/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'underscore',
    'mageUtils'
], function (_, utils) {
    'use strict';

    return function (target) {
        target.defaults.previewTpl = 'CommunityEngineering_JapaneseAddress/address-preview';

        return target.extend({

            /**
             * Build preview.
             *
             * @param {Array} items
             * @returns {String}
             */
            getPreview: function (items) {
                var elems = this.indexed(),
                    displayed = this.displayed,
                    preview;

                items = items.map(function (index) {
                    var elem = elems[index];

                    preview = elem && elem.visible() ? elem.getPreview() : '';

                    preview = Array.isArray(preview) ?
                        _.compact(preview).join(index === 'street' ? ' ' : ', ') :
                        preview;

                    if (preview && index === 'lastnamekana') {
                        preview = '(' + preview;

                        if (!elems.firstnamekana.getPreview()) {
                            preview += ')';
                        }
                    }

                    if (preview && index === 'firstnamekana') {
                        preview += ')';

                        if (!elems.lastnamekana.getPreview()) {
                            preview = '(' + preview;
                        }
                    }

                    utils.toggle(displayed, index, !!preview);

                    return preview;
                });

                this.noPreview(!displayed.length);

                return _.compact(items);
            }
        });
    };
});
