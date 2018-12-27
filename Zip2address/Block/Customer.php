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
     * @param Template\Context $context
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MagentoJapan\Zip2address\Helper\Data $helper,
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
