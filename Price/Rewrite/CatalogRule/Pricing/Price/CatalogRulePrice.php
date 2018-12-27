<?php
declare(strict_types=1);

namespace MagentoJapan\Price\Rewrite\CatalogRule\Pricing\Price;

use Magento\CatalogRule\Pricing\Price\CatalogRulePrice as Original;
use Magento\CatalogRule\Model\ResourceModel\RuleFactory;
use Magento\CatalogRule\Model\ResourceModel\Rule;
use Magento\Framework\App\ObjectManager;

/**
 * Catalog price rules JPY currently formatting.
 */
class CatalogRulePrice extends Original
{
    /**
     * @var \Magento\CatalogRule\Model\ResourceModel\Rule
     */
    private $ruleResource;

    /**
     * Get Rule resource.
     *
     * @return Rule
     * @deprecated
     */
    private function getRuleResource()
    {
        if (null === $this->ruleResource) {
            $this->ruleResource = ObjectManager::getInstance()->get(Rule::class);
        }

        return $this->ruleResource;
    }

    /**
     * Returns catalog rule value
     *
     * @return float|boolean
     */
    public function getValue()
    {
        if (null === $this->value) {
            if ($this->product->hasData(self::PRICE_CODE)) {
                $this->value = floatval($this->product->getData(self::PRICE_CODE)) ?: false;
            } else {
                $this->value = $this->getRuleResource()
                    ->getRulePrice(
                        $this->dateTime->scopeDate($this->storeManager->getStore()->getId()),
                        $this->storeManager->getStore()->getWebsiteId(),
                        $this->customerSession->getCustomerGroupId(),
                        $this->product->getId()
                    );
                $this->value = $this->value ? floatval($this->value) : false;
            }
            if ($this->value) {
                $this->value = $this->priceCurrency->convertAndRound($this->value);
            }
        }
        return $this->value;
    }
}
