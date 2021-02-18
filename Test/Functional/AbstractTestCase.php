<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Functional;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Api\Data\RuleInterfaceFactory;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;
use Yireo\SalesBlock2\Model\Rule;
use Yireo\SalesBlock2\Model\Rule\Service as RuleService;
use Yireo\SalesBlock2ByEmail\Utils\CurrentEmail;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @param bool $reset
     * @return RuleService
     */
    protected function getRuleService(bool $reset = true): RuleService
    {
        $ruleService = $this->getObjectManager()->get(RuleService::class);
        if ($reset) {
            $ruleService->reset();
        }

        return $ruleService;
    }

    /**
     * @param string $label
     * @param array $condition
     * @return RuleInterface
     */
    protected function createRule(string $label, array $condition): RuleInterface
    {
        /** @var Rule $rule */
        $rule = $this->getObjectManager()->get(RuleInterfaceFactory::class)->create();
        $rule->setLabel($label);
        $rule->setStatus(1);
        $rule->setConditions(json_encode([$condition]));
        return $rule;
    }

    /**
     * @param string $label
     * @param array $condition
     */
    protected function addRule(string $label, array $condition)
    {
        $rule = $this->createRule($label, $condition);
        $this->getRuleService(false)->addRule($rule);
    }

    /**
     * @param string $label
     * @param array $condition
     */
    protected function setRule(string $label, array $condition)
    {
        $rule = $this->createRule($label, $condition);
        $this->getRuleService()->addRule($rule);
    }

    /**
     * @return RuleHelper
     */
    protected function getRuleHelper(): RuleHelper
    {
        return $this->getObjectManager()->get(RuleHelper::class);
    }

    /**
     * @return CurrentEmail
     */
    protected function getCurrentEmail(): CurrentEmail
    {
        return $this->getObjectManager()->get(CurrentEmail::class);
    }

    /**
     * @param string $customerEmail
     * @return OrderInterface
     */
    protected function getOrder(string $customerEmail): OrderInterface
    {
        /** @var OrderRepositoryInterface $orderRepository */
        $orderRepository = $this->getObjectManager()->get(OrderRepositoryInterface::class);
        $searchCriteriaBuilder = $this->getObjectManager()->get(SearchCriteriaBuilder::class);
        $searchResults = $orderRepository->getList($searchCriteriaBuilder->create());
        $orders = $searchResults->getItems();
        $order = array_shift($orders);
        $order->setCustomerEmail($customerEmail);

        return $order;
    }

    /**
     * @return ObjectManagerInterface
     */
    protected function getObjectManager(): ObjectManagerInterface
    {
        return ObjectManager::getInstance();
    }
}
