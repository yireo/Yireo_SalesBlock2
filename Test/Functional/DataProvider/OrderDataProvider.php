<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Functional\DataProvider;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderDataProvider
{
    /**
     * @return OrderInterface
     */
    public function getOrder(): OrderInterface
    {
        /** @var OrderRepositoryInterface $orderRepository */
        $orderRepository = $this->getObjectManager()->get(OrderRepositoryInterface::class);
        $searchCriteriaBuilder = $this->getObjectManager()->get(SearchCriteriaBuilder::class);
        $searchResults = $orderRepository->getList($searchCriteriaBuilder->create());
        $orders = $searchResults->getItems();
        return array_shift($orders);
    }

    /**
     * @return ObjectManagerInterface
     */
    private function getObjectManager(): ObjectManagerInterface
    {
        return ObjectManager::getInstance();
    }
}