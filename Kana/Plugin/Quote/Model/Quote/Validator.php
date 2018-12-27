<?php
namespace MagentoJapan\Kana\Plugin\Quote\Model\Quote;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteValidator;
use MagentoJapan\Kana\Model\Config\System;

/**
 * Validate Customer's name in Kana.
 */
class Validator
{
    /**
     * @var System
     */
    private $system;

    /**
     * @param System $system
     */
    public function __construct(
        System $system
    ) {
        $this->system = $system;
    }

    /**
     * @param QuoteValidator $subject
     * @param \Magento\Quote\Model\Quote $arguments
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeValidateBeforeSubmit(
        QuoteValidator $subject,
        Quote $arguments
    ) {
        if ($this->system->getRequireKana()) {
            $this->hasKana($arguments);
        }
    }

    /**
     * @param Quote $quote
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    private function hasKana(Quote $quote)
    {
        if (!$quote->getFirstnamekana()) {
            throw new \Magento\Framework\Exception\ValidatorException(
                __("Firstname kana is required field. Your address doesn't have it.")
            );
        }

        if (!$quote->getLastnamekana()) {
            throw new \Magento\Framework\Exception\ValidatorException(
                __("Lastname kana is required field. Your address doesn't have it.")
            );
        }
    }
}
