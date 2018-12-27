<?php

namespace MagentoJapan\Price\Plugin\Block\Product;

use Magento\Catalog\Block\Product\View as OriginalView;

/**
 * Format JPY currency at Product View.
 */
class View
{
    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $jsonEncoder;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventManager;

    /**
     * @var \Magento\Framework\Locale\FormatInterface
     */
    private $localeFormat;

    /**
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     */
    public function __construct(
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        $this->jsonEncoder = $jsonEncoder;
        $this->localeFormat = $localeFormat;
        $this->eventManager = $eventManager;
    }

    /**
     * @param OriginalView $view
     * @param \Closure $proceed
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetJsonConfig(
        OriginalView $view,
        \Closure $proceed
    ) {
        /* @var $product \Magento\Catalog\Model\Product */
        $product = $view->getProduct();
        if (!$view->hasOptions()) {
            $config = [
                'productId' => $product->getId(),
                'priceFormat' => $this->localeFormat->getPriceFormat()
            ];
            return $this->jsonEncoder->encode($config);
        }
        $tierPrices = [];
        $tierPricesList = $product->getPriceInfo()->getPrice('tier_price')->getTierPriceList();
        foreach ($tierPricesList as $tierPrice) {
            $tierPrices[] = $tierPrice['price']->getValue();
        }
        $config = [
            'productId'   => $product->getId(),
            'priceFormat' => $this->localeFormat->getPriceFormat(),
            'prices'      => [
                'oldPrice'   => [
                    'amount'      => $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue(),
                    'adjustments' => []
                ],
                'basePrice'  => [
                    'amount'      => $product->getPriceInfo()->getPrice('final_price')->getAmount()->getBaseAmount(),
                    'adjustments' => []
                ],
                'finalPrice' => [
                    'amount'      => $product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue(),
                    'adjustments' => []
                ]
            ],
            'idSuffix'    => '_clone',
            'tierPrices'  => $tierPrices
        ];
        $responseObject = new \Magento\Framework\DataObject();
        $this->eventManager->dispatch('catalog_product_view_config', ['response_object' => $responseObject]);
        if (is_array($responseObject->getAdditionalOptions())) {
            foreach ($responseObject->getAdditionalOptions() as $option => $value) {
                $config[$option] = $value;
            }
        }
        return $this->jsonEncoder->encode($config);
    }
}
