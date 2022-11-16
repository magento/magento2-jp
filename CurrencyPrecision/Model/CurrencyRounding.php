<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Model;

use CommunityEngineering\CurrencyPrecision\Model\Config\CurrencyRoundingConfig;
use CommunityEngineering\CurrencyPrecision\Model\Config\Source\RoundingMode;
use Magento\Directory\Model\Currency;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ScopeResolverInterface;
use Magento\Framework\Locale\ResolverInterface as LocalResolverInterface;
use Magento\Framework\NumberFormatterFactory;
use Magento\Framework\Serialize\Serializer\Json;

use function hash;
use function sprintf;

/**
 * Currency rounding service.
 */
class CurrencyRounding
{
    /**
     * @var \Magento\Framework\App\ScopeResolverInterface
     */
    protected $scopeResolver;

    /**
     * @var LocalResolverInterface
     */
    protected $localeResolver;

    /**
     * @var CurrencyRoundingConfig
     */
    protected $currencyRoundingConfig;

    /**
     * @var \Magento\Framework\NumberFormatterFactory
     */
    protected $numberFormatterFactory;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;

    /**
     * @var \Magento\Framework\NumberFormatter|null
     */
    protected $_numberFormatter;

    protected $_numberFormatterCache = [];

    /**
     * CurrencyRounding constructor.
     *
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     * @param \CommunityEngineering\CurrencyPrecision\Model\Config\CurrencyRoundingConfig $currencyRoundingConfig
     * @param \Magento\Framework\NumberFormatterFactory|null $numberFormatterFactory
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        ScopeResolverInterface $scopeResolver,
        LocalResolverInterface $localeResolver,
        CurrencyRoundingConfig $currencyRoundingConfig,
        NumberFormatterFactory $numberFormatterFactory = null,
        Json $serializer = null
    ) {
        $this->scopeResolver = $scopeResolver;
        $this->localeResolver = $localeResolver;
        $this->currencyRoundingConfig = $currencyRoundingConfig;
        $this->numberFormatterFactory = $numberFormatterFactory ?: ObjectManager::getInstance()->get(NumberFormatterFactory::class);
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
    }

    /**
     * Determine what precision has main currency unit.
     *
     * Main currency precision is defined by subunits. E.g. fo US dollar precision is 2 as 1 USD equals to 100 cents
     * so USD has 2 significant fraction digits. Japanese Yen (JPY) has precision 0 as no subunits currently in use.
     *
     * @param string $currencyCode
     *
     * @return int
     */
    public function getPrecision(string $currencyCode): int
    {
        $formatter = $this->createCurrencyFormatter($currencyCode);

        $precision = $formatter->getAttribute(\NumberFormatter::MAX_FRACTION_DIGITS);

        return $precision;
    }

    /**
     * Round currency to significant precision.
     *
     * Rounding method may be configured at admin page at
     *
     * @param string $currencyCode
     * @param float $amount
     *
     * @return float
     */
    public function round(string $currencyCode, float $amount): float
    {
        $roundingMode = $this->getRoundingMode();

        /**
         * Fix problem with 12 000 000, 1 200 000
         *
         * %f - the argument is treated as a float, and presented as a floating-point number (locale aware).
         * %F - the argument is treated as a float, and presented as a floating-point number (non-locale aware).
         */
        $amount = (float)sprintf("%F", $amount);

        $formatter = $this->createCurrencyFormatter($currencyCode);

        $formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, $roundingMode);
        $formatted = $formatter->formatCurrency(
            $amount,
            $formatter->getTextAttribute(\NumberFormatter::CURRENCY_CODE)
        );

        $rounded = $formatter->parse($formatted, \NumberFormatter::TYPE_DOUBLE);

        return $rounded;
    }

    /**
     * Create Intl Number Formatter for currency.
     *
     * @param string $currencyCode
     * @param array $options
     * @param string|null $localeCode
     * @param string|int|null $style
     *
     * @return \Magento\Framework\NumberFormatter
     */
    public function createCurrencyFormatter(string $currencyCode, array $options = [], $localeCode = null, $style = null): \Magento\Framework\NumberFormatter
    {
        $this->_numberFormatter = $this->getNumberFormatter($currencyCode, $options);

        return $this->_numberFormatter;
    }

    /**
     * @param string|null $currencyCode
     * @param array $options
     * @param string|null $localeCode
     * @param string|int|null $style
     *
     * @return \Magento\Framework\NumberFormatter
     */
    public function getNumberFormatter($currencyCode = null, array $options = [], $localeCode = null, $style = null): \Magento\Framework\NumberFormatter
    {
        /** @var Currency $currency */
        if (!$currencyCode) {
            $currency = $this->scopeResolver->getScope()->getCurrentCurrency();
            $currencyCode = $currency->getCode();
        }

        $localeCode = $localeCode ?: $this->localeResolver->getLocale();
        $key = 'currency_' . hash(
                'sha256',
                ($localeCode . $this->serializer->serialize($options) . $currencyCode)
            );

        if (!isset($this->numberFormatterCache[$key])) {
            $this->_numberFormatter = $this->numberFormatterFactory->create([
                'locale' => $currencyCode ? $localeCode . '@currency=' . $currencyCode : $localeCode,
                'style' => $style ?: \NumberFormatter::CURRENCY,
            ]);

            $this->_numberFormatterCache[$key] = $this->_numberFormatter;
        }

        return $this->_numberFormatterCache[$key];
    }

    /**
     * Get Intl rounding mode.
     *
     * Read configured rounding mode and map to Intl constant value.
     *
     * @return int
     */
    protected function getRoundingMode(): int
    {
        $roundingModesMap = [
            RoundingMode::UP => \NumberFormatter::ROUND_UP,
            RoundingMode::CEILING => \NumberFormatter::ROUND_CEILING,
            RoundingMode::DOWN => \NumberFormatter::ROUND_DOWN,
            RoundingMode::FLOOR => \NumberFormatter::ROUND_FLOOR,
            RoundingMode::HALFUP => \NumberFormatter::ROUND_HALFUP,
            RoundingMode::HALFEVEN => \NumberFormatter::ROUND_HALFEVEN,
            RoundingMode::HALFDOWN => \NumberFormatter::ROUND_HALFDOWN,
        ];
        $configuredMode = $this->currencyRoundingConfig->getRoundingMode();

        if (!isset($roundingModesMap[$configuredMode])) {
            throw new \LogicException(
                sprintf('Configured rounding mode "%s" is not implemented.', $configuredMode)
            );
        }

        return $roundingModesMap[$configuredMode];
    }
}
