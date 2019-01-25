<?php
declare(strict_types=1);

namespace MagentoJapan\Price\Observer;

use \Magento\CurrencySymbol\Model\System\CurrencysymbolFactory;
use \Magento\Framework\Event\ObserverInterface;
use \MagentoJapan\Price\Model\Config\System;

/**
 * Modify currency options display.
 */
class ModifyCurrencyOptions implements ObserverInterface
{
    /**
     * @var CurrencysymbolFactory
     */
    private $symbolFactory;

    /**
     * @var System
     */
    private $system;

    /**
     * @param CurrencysymbolFactory $symbolFactory Currency Symbol Factory
     * @param System $system
     */
    public function __construct(
        CurrencysymbolFactory $symbolFactory,
        System $system
    ) {
        $this->symbolFactory = $symbolFactory;
        $this->system = $system;
    }

    /**
     * Generate options for currency displaying with custom currency symbol.
     *
     * @param \Magento\Framework\Event\Observer $observer Observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $baseCode = $observer->getEvent()->getBaseCode();
        $currencyOptions = $observer->getEvent()->getCurrencyOptions();
        $originalOptions = $currencyOptions->getData();
        $currencyOptions->setData(
            $this->getCurrencyOptions(
                $baseCode,
                $originalOptions
            )
        );

        return $this;
    }

    /**
     * Get currency display options.
     *
     * @param string $baseCode Base currency code
     * @param array $originalOptions Currency Options
     * @param array $originalOptions
     * @return array
     */
    protected function getCurrencyOptions($baseCode, $originalOptions)
    {
        $currencyOptions = [];
        if (in_array($baseCode, $this->system->getIntegerCurrencies())) {
            $currencyOptions['precision'] = '0';
        }

        return array_merge($originalOptions, $currencyOptions);
    }
}
