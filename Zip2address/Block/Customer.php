<?php
namespace MagentoJapan\Zip2address\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use MagentoJapan\Zip2address\Helper\Data;

/**
 * Customer block.
 *
 * @api
 */
class Customer extends Template
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @param Context $context
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * Get helper.
     *
     * @return Data
     * @deprecated
     */
    public function getHelper(): Data
    {
        return $this->helper;
    }
}
