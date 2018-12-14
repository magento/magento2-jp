<?php
namespace MagentoJapan\Price\Model\Directory\Plugin;

use \Magento\Directory\Model\Currency;
use \MagentoJapan\Price\Model\Config\System;

class Precision
{
    /**
     * System Configuration
     *
     * @var System
     */
    private $system;

    /**
     * Precision constructor.
     * @param System $system
     */
    public function __construct(
        System $system
    ) {
        $this->system = $system;
    }

    /**
     * Modify price precision for JPY
     *
     * @param Currency $subject          Currency Object
     * @param \Closure $proceed          Closure
     * @param float    $price            Price
     * @param int      $precision        Currency Precision
     * @param array    $options          Currency options array
     * @param bool     $includeContainer Include container flag
     * @param bool     $addBrackets      Add brackets flag
     *
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
        if (in_array($subject->getCode(), $this->system->getIntegerCurrencies())) {
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
            $addBrackets);
    }

    /**
     * Modify Currency Position
     *
     * @param Currency $subject
     * @param \Closure $proceed
     * @param $price
     * @param array $options
     * @return mixed
     */
    public function aroundFormatTxt(
        Currency  $subject,
        \Closure $proceed,
        $price,
        $options = []
    )
    {
        if ($subject->getCode() == 'JPY') {
            $position = $this->system->getSymbolPosition();
            $options['position'] = (int)$position;
            if($options['position'] == \Zend_Currency::RIGHT) {
                $options['symbol'] = __('Yen');
            }
        }

        return $proceed(
            $price,
            $options
        );
    }

}