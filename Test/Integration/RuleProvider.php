<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration;

use Magento\TestFramework\ObjectManager;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;

/**
 * Class RuleProvider
 * @package Yireo\SalesBlock2\Test\Integration
 */
class RuleProvider
{
    /**
     * @param string $conditionName
     * @param string $conditionValue
     * @param bool $enabled
     * @return RuleInterface
     */
    public function createRule(string $conditionName, string $conditionValue, bool $enabled): RuleInterface
    {
        $conditions = [
            ['name' => $conditionName, 'value' => $conditionValue]
        ];

        $rule = $this->getRuleRepository()->getEmpty();
        $rule->setLabel('Test');
        $rule->setStatus((int)$enabled);
        $rule->setConditions(json_encode($conditions));
        $this->getRuleRepository()->save($rule);
        return $rule;
    }

    /**
     * @return RuleRepositoryInterface
     */
    private function getRuleRepository(): RuleRepositoryInterface
    {
        return ObjectManager::getInstance()->get(RuleRepositoryInterface::class);
    }
}
