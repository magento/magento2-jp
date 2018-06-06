<?php
namespace MagentoJapan\StoreAddress\Plugin\Store\Model\Address;

use Magento\Store\Model\Address\Renderer as BaseRenderer;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\DataObject;
use Magento\Store\Model\ScopeInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;

class Renderer
{

    /**
     *
     */
    const CONFIG_FORMAT = 'general/store_information/format';
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Constructor
     *
     * @param EventManager $eventManager
     * @param FilterManager $filterManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        EventManager $eventManager,
        FilterManager $filterManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->eventManager = $eventManager;
        $this->filterManager = $filterManager;
        $this->scopeConfig = $scopeConfig;
    }


    /**
     * @param BaseRenderer $renderer
     * @param \Closure $proceed
     * @param DataObject $storeInfo
     * @param string $type
     * @return string
     */
    public function aroundFormat(
        BaseRenderer $renderer,
        \Closure $proceed,
        DataObject $storeInfo,
        $type = 'html'
    ) {
        $format = $this->scopeConfig
            ->getValue(self::CONFIG_FORMAT, ScopeInterface::SCOPE_STORE);

        $this->eventManager
            ->dispatch('store_address_format',
                        [
                            'type' => $type,
                            'store_info' => $storeInfo
                        ]);
        $address = $this->filterManager->template(
            $format,
            ['variables' => $storeInfo->getData()]
        );

        return $address;
    }
}