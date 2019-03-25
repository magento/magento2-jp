<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseAddress\Plugin\Customer\Block\Form;

use Magento\Customer\Block\Form\Register;
use CommunityEngineering\JapaneseAddress\Model\Config\CustomerRegistrationConfig;

/**
 * Use configuration to display address at registration form.
 */
class RegisterWithAddress
{
    /**
     * @var CustomerRegistrationConfig
     */
    private $config;

    /**
     * @param CustomerRegistrationConfig $config
     */
    public function __construct(CustomerRegistrationConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Use config value if it does not defined at runtime.
     *
     * @param Register $form
     * @param mixed $result
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    private function getShowAddressFields(Register $form, $result)
    {
        if ($result !== null) {
            return $result;
        }
        return $this->config->isAddressRequired();
    }

    // @codingStandardsIgnoreStart
    /**
     * Puginize magic methods
     *
     * @param Register $form
     * @param mixed $result
     * @param string $method
     * @return mixed
     */
    public function after__call(Register $form, $result, $method)
    {
        switch ($method) {
            case 'getShowAddressFields':
                return $this->getShowAddressFields($form, $result);
            default:
                return $result;
        }
    }
    // @codingStandardsIgnoreEnd
}
