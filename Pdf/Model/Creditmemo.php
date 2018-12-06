<?php
namespace MagentoJapan\Pdf\Model;

use Magento\Sales\Model\Order\Pdf\Creditmemo as BaseCreditmemo;
use MagentoJapan\Pdf\ModelConfig\Service;

class Creditmemo extends BaseCreditmemo
{
    /**
     * @var Service
     */
    private $service;


    /**
     *
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Sales\Model\Order\Pdf\Config $pdfConfig
     * @param \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory
     * @param \Magento\Sales\Model\Order\Pdf\ItemsFactory $pdfItemsFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Sales\Model\Order\Address\Renderer $addressRenderer
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     * @param Service $service
     * @param array $data
     */
    public function __construct(
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Sales\Model\Order\Pdf\Config $pdfConfig,
        \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory,
        \Magento\Sales\Model\Order\Pdf\ItemsFactory $pdfItemsFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        Service $service,
        array $data = []
    )
    {
        $this->service = $service;
        parent::__construct(
            $paymentData,
            $string,
            $scopeConfig,
            $filesystem,
            $pdfConfig,
            $pdfTotalFactory,
            $pdfItemsFactory,
            $localeDate,
            $inlineTranslation,
            $addressRenderer,
            $storeManager,
            $localeResolver,
            $data
        );
    }

    /**
     * @param $object
     * @param int $size
     * @return mixed
     */
    protected function _setFontRegular($object, $size = 7)
    {
        if ($this->service->getJapaneseFontIsActive()) {
            $fontpath = $this->service->getJapaneseFont();
            $font = \Zend_Pdf_Font::fontWithPath($fontpath);
            $object->setFont($font, $size);
            return $font;
        }
        return parent::_setFontRegular($object, $size);
    }

    /**
     * @param $object
     * @param int $size
     * @return mixed
     */
    protected function _setFontBold($object, $size = 7)
    {
        if ($this->service->getJapaneseFontIsActive()) {
            $fontpath = $this->service->getJapaneseFont();
            $font = \Zend_Pdf_Font::fontWithPath($fontpath);
            $object->setFont($font, $size);
            return $font;
        }
        return parent::_setFontBold($object, $size);
    }

    /**
     * @param $object
     * @param int $size
     * @return mixed
     */
    protected function _setFontItalic($object, $size = 7)
    {
        if ($this->service->getJapaneseFontIsActive()) {
            $fontpath = $this->service->getJapaneseFont();
            $font = \Zend_Pdf_Font::fontWithPath($fontpath);
            $object->setFont($font, $size);
            return $font;
        }
        return parent::_setFontItalic($object, $size);
    }
}