/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            postalCodeDataProvider: 'MagentoJapan_PostalCode/js/service/madefor-postal-code-api'
        }
    },
    config: {
        mixins: {
            'Magento_Ui/js/form/element/post-code': {
                'MagentoJapan_PostalCode/js/ui/form-postal-code-element': true
            }
        }
    }
};
