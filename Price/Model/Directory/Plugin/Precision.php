<?php
namespace MagentoJapan\Price\Model\Directory\Plugin;

use Magento\Directory\Model\Currency;
use MagentoJapan\Price\Helper\Data;

class Precision
{
    /**
     * Helper
     *
     * @var \MagentoJapan\Price\Helper\Data
     */
    private $helper;

    /**
     * Precision constructor.
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
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
            $position = $this->helper->getSymbolPosition();
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