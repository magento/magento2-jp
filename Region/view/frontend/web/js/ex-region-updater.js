/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'regionUpdater'
], function ($) {
    'use strict';

    $.widget('mage.jpRegionUpdater', $.mage.regionUpdater, {
        /**
         * @override
         */
        _create: function () {
            this._super();
            this._updateRegion(this.element.val());
        }
    });

    return $.mage.jpRegionUpdater;
});
