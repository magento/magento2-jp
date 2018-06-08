<?php
namespace MagentoJapan\Kana\Plugin\Quote\Model\Quote;

use \Magento\Quote\Model\QuoteValidator;
use \Magento\Quote\Model\Quote;
use \MagentoJapan\Kana\Helper\Data;

class Validator
{
    /**
     * @var \MagentoJapan\Kana\Helper\Data
     */
    private $helper;

    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param QuoteValidator $subject
     * @param \Magento\Quote\Model\Quote $arguments
     * @return bool
     */
    public function beforeValidate(
        QuoteValidator $subject,
        Quote $arguments
    ) {
        if($this->helper->getRequireKana()) {
            $this->hasKana($arguments);
        }
    }


    private function hasKana(Quote $quote)
    {
        if(!$quote->getFirstnamekana())
        {
            throw new \Magento\Framework\Exception\ValidatorException(
                __("Firstname kana is required field. Your address doesn't have it.")
            );
        }

        if(!$quote->getLastnamekana())
        {
            throw new \Magento\Framework\Exception\ValidatorException(
                __("Lastname kana is required field. Your address doesn't have it.")
            );
        }
    }
}