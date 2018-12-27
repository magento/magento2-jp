<?php
namespace MagentoJapan\Zip2address\Block;

use Magento\Framework\View\Element\Template;
use MagentoJapan\Zip2address\Helper\Data;

/**
 * @api
 */
class Customer extends Template
{
    /**
     * @var \MagentoJapan\Zip2address\Helper\Data
     */
    protected $helper;

    /**
     * Customer constructor.
     * @param \MagentoJapan\Zip2address\Helper\Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \MagentoJapan\Zip2address\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * @return \MagentoJapan\Zip2address\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }
}
