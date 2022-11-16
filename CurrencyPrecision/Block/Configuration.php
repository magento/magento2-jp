<?php

namespace CommunityEngineering\CurrencyPrecision\Block;

use CommunityEngineering\CurrencyPrecision\Model\Config\CurrencyRoundingConfig;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Context;
use Magento\Store\Model\StoreManagerInterface;

class Configuration extends Template
{
    protected $_template = 'CommunityEngineering_CurrencyPrecision::js/configuration.phtml';

    /**
     * @var \CommunityEngineering\CurrencyPrecision\Model\Config\CurrencyRoundingConfig
     */
    protected $roundingConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonSerializer;

    /**
     * Configuration constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \CommunityEngineering\CurrencyPrecision\Model\Config\CurrencyRoundingConfig $currencyRoundingConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CurrencyRoundingConfig $currencyRoundingConfig,
        StoreManagerInterface $storeManager,
        Json $jsonSerializer,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->roundingConfig = $currencyRoundingConfig;
        $this->storeManager = $storeManager;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrencyCode(): string
    {
        return $this->storeManager->getStore()->getCurrentCurrencyCode();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConfiguration(): array {
        return [
            'code' => $this->getCurrencyCode(),
            'value' => $this->roundingConfig->getRoundingMode(),
        ];
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConfigurationJson(): string {
        return $this->jsonSerializer->serialize($this->getConfiguration());
    }
}
