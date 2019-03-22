<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseStoreAddress\Plugin\Store\Model\Address;

use Magento\Store\Model\Address\Renderer as BaseRenderer;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\DataObject;
use CommunityEngineering\JapaneseStoreAddress\Model\Config\StoreAddressConfig;

/**
 * Format store address based on admin configuration.
 *
 * This functional should be moved from plugin to Magento core eventually.
 */
class Renderer
{
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @var StoreAddressConfig
     */
    private $config;

    /**
     * @param EventManager $eventManager
     * @param FilterManager $filterManager
     * @param StoreAddressConfig $config
     */
    public function __construct(
        EventManager $eventManager,
        FilterManager $filterManager,
        StoreAddressConfig $config
    ) {
        $this->eventManager = $eventManager;
        $this->filterManager = $filterManager;
        $this->config = $config;
    }

    /**
     * Format store address display for JP locale.
     *
     * @param BaseRenderer $renderer
     * @param \Closure $proceed
     * @param DataObject $storeInfo
     * @param string $type
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundFormat(
        BaseRenderer $renderer,
        \Closure $proceed,
        DataObject $storeInfo,
        $type = 'html'
    ) {
        $this->eventManager->dispatch(
            'store_address_format',
            [
                'type' => $type,
                'store_info' => $storeInfo
            ]
        );
        $address = $this->filterManager->template(
            $this->config->getAddressTemplate($type),
            ['variables' => $storeInfo->getData()]
        );

        return $address;
    }
}
