<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseYenFormatting\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\JapanPrice\Model\CurrencyPrecision;
use CommunityEngineering\JapaneseYenFormatting\Model\CurrencyFormatOptionModifiers;

/**
 * Inject currency formatting options into default currency options.
 */
class ModifyCurrencyFormattingOptions implements ObserverInterface
{
    /**
     * System Configuration.
     *
     * @var CurrencyFormatOptionModifiers
     */
    private $currencyFormatOptionModifiers;

    /**
     * @param CurrencyFormatOptionModifiers $currencyFormatOptionModifiers
     */
    public function __construct(
        CurrencyFormatOptionModifiers $currencyFormatOptionModifiers
    ) {
        $this->currencyFormatOptionModifiers = $currencyFormatOptionModifiers;
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
            $currencyOptions->addData($this->currencyFormatOptionModifiers->getOptions($currencyCode));
        }
        return $this;
    }
}
