var config = {
    map: {
        '*': {
            zip2address: 'MagentoJapan_Zip2address/js/zip2address',
            zip2addresspostcode: 'MagentoJapan_Zip2address/js/ui/form/element/post-code'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/model/postcode-validator': {
                'MagentoJapan_Zip2address/js/postcode-validator-mixin': true
            }
        }
    }
};
