<?php

namespace MagentoJapan\Price\Model\Directory\Plugin;

use Magento\Directory\Model\Currency;
use MagentoJapan\Price\Helper\Data;

/**
 * Modify price precision for JPY.
 */
class Precision
{
    /**
     * @var \MagentoJapan\Price\Helper\Data
     */
    private $helper;

    /**
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Modify price precision for JPY.
     *
     * @param Currency $subject
     * @param \Closure $proceed
     * @param float $price
     * @param int $precision
     * @param array $options
     * @param bool $includeContainer
     * @param bool $addBrackets
     * @return mixed
     */
    public function aroundFormatPrecision(
        Currency  $subject,
        \Closure $proceed,
        $price,
        $precision = 2,
        $options = [],
        $includeContainer = true,
        $addBrackets = false
    ) {
        if (in_array($subject->getCode(), $this->helper->getIntegerCurrencies())) {
            $precision = '0';
            if (isset($options['precision'])) {
                $options['precision'] = '0';
            }
        }
        return $proceed(
            $price,
            $precision,
            $options,
            $includeContainer,
            $addBrackets
        );
    }

    /**
     * Modify Currency Position.
     *
     * @param Currency $subject
     * @param \Closure $proceed
     * @param float $price
     * @param array $options
     * @return mixed
     */
    public function aroundFormatTxt(
        Currency  $subject,
        \Closure $proceed,
        $price,
        $options = []
    ) {
        if ($subject->getCode() == 'JPY') {
            $position = $this->helper->getSymbolPosition();
            $options['position'] = (int)$position;
            if ($options['position'] == \Zend_Currency::RIGHT) {
                $options['symbol'] = __('Yen');
            }
        }

        return $proceed(
            $price,
            $options
        );
    }
}
