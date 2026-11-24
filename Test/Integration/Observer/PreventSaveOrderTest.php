<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Observer;

use Magento\Framework\Event;
use Magento\Framework\Event\ConfigInterface as EventConfig;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Matcher\MatcherList;
use Yireo\SalesBlock2\Observer\PreventSaveOrder;
use Yireo\SalesBlock2\Test\Integration\RuleProvider;
use Yireo\SalesBlock2\Test\Integration\Stub\AlwaysMatchMatcher;

class PreventSaveOrderTest extends TestCase
{
    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoConfigFixture current_store salesblock/settings/enabled 1
     */
    public function testNewOrderIsBlocked(): void
    {
        $this->expectException(LocalizedException::class);
        $this->getObserver()->execute($this->createObserver($this->createOrder(null)));
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoConfigFixture current_store salesblock/settings/enabled 1
     */
    public function testExistingOrderIsNotBlocked(): void
    {
        $this->getObserver()->execute($this->createObserver($this->createOrder(42)));
        $this->assertTrue(true);
    }

    /**
     * @magentoAppArea adminhtml
     */
    public function testRemoveQuoteObserverIsNotActiveInAdmin(): void
    {
        $observers = Bootstrap::getObjectManager()->get(EventConfig::class)->getObservers('sales_quote_save_after');
        $this->assertArrayNotHasKey('yireo_salesblock2_remove_quote', $observers);
    }

    /**
     * @magentoAppArea frontend
     */
    public function testRemoveQuoteObserverIsActiveInFrontend(): void
    {
        $observers = Bootstrap::getObjectManager()->get(EventConfig::class)->getObservers('sales_quote_save_after');
        $this->assertArrayHasKey('yireo_salesblock2_remove_quote', $observers);
    }

    private function getObserver(): PreventSaveOrder
    {
        $objectManager = Bootstrap::getObjectManager();
        $objectManager->configure([
            MatcherList::class => [
                'arguments' => [
                    'matchers' => [
                        'test_always' => ['instance' => AlwaysMatchMatcher::class],
                    ],
                ],
            ],
        ]);

        $objectManager->get(RuleProvider::class)->createRule(AlwaysMatchMatcher::CODE, '*', true);
        return $objectManager->create(PreventSaveOrder::class);
    }

    private function createOrder(?int $orderId): Order
    {
        $order = Bootstrap::getObjectManager()->create(Order::class);
        $order->setId($orderId);
        $order->setCustomerEmail('john@example.com');
        return $order;
    }

    private function createObserver(Order $order): Observer
    {
        $event = new Event(['order' => $order, 'name' => 'sales_order_save_before']);
        return new Observer(['event' => $event]);
    }
}
