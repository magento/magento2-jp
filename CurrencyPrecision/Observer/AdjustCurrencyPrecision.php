<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use CommunityEngineering\CurrencyPrecision\Model\CurrencyRounding;

/**
 * Adjust default currency options used during currency instantiation to specify correct precision.
 */
class AdjustCurrencyPrecision implements ObserverInterface
{
    /**
     * @var CurrencyRounding
     */
    private $currencyRounding;

    /**
     * @param CurrencyRounding $currencyRounding
     */
    public function __construct(
        CurrencyRounding $currencyRounding
    ) {
        $this->currencyRounding = $currencyRounding;
    }

    /**
     * @inheritdoc
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        $currencyCode = $event->getData('base_code');
        $currencyOptions = $event->getData('currency_options');

        if ($currencyCode !== null) {
            $currencyOptions->addData($this->getPrecisionOptions($currencyCode));
        }

        return $this;
    }

    /**
     * Get options to configure required precision.
     *
     * @param string $currencyCode
     * @return array
     */
    private function getPrecisionOptions(string $currencyCode): array
    {
        return [
            'precision' => $this->currencyRounding->getPrecision($currencyCode)
        ];
    }
}
