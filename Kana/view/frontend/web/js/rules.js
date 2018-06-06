require(
    [
        'Magento_Ui/js/lib/validation/validator',
        'jquery',
        'mage/translate'
    ], function(validator, $){
        validator.addRule(
            'validate-hiragana',
            function (value) {
                return /^([\u3040-\u309F|\u30FB-\u30FC])*$/.test(value);
            },
            $.mage.__('Please use Hiragana only in this field.')
        );
        validator.addRule(
            'validate-katakana',
            function (value) {
                return /^([\u30A1-\u30FC])*$/.test(value);
            },
            $.mage.__('Please use full width Katakana only in this field.')
        );
        validator.addRule(
            'validate-kana',
            function (value) {
                return /^([\u3040-\u309F|\u30FB-\u30FC|\u30A1-\u30FC])*$/.test(value);
            },
            $.mage.__('Please use full width kana only in this field.')
        );
        validator.addRule(
            'validate-normal',
            function (value) {
                return !/^([!"#$%&'()\*\+\-\.,\/:;<=>?@\[\\\]^_`{|}~])*$/.test(value);
            },
            $.mage.__('Please do not use sign characters in this field.')
        );
        validator.addRule(
            'validate-postcode',
            function (value) {
                return /^(\d{3}\-\d{4}|\d{7})$/.test(value);
            },
            $.mage.__('Please set postcode right format like 000-0000 or 0000000.')
        );
    });