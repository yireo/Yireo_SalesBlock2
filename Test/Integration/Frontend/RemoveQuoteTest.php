<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Frontend;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Matcher\MatcherList;
use Yireo\SalesBlock2\Observer\RemoveQuote;
use Yireo\SalesBlock2\Test\Integration\RuleProvider;
use Yireo\SalesBlock2\Test\Integration\Stub\AlwaysMatchMatcher;

class RemoveQuoteTest extends TestCase
{
    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Checkout/_files/quote_with_simple_product_saved.php
     * @magentoConfigFixture current_store salesblock/settings/enabled 1
     * @magentoConfigFixture default/salesblock/settings/destroy_cart 1
     */
    public function testQuoteIsDestroyedOnceWithoutRecursion(): void
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

        /** @var Quote $quote */
        $quote = $objectManager->get(QuoteFactory::class)->create()
            ->load('test_order_with_simple_product_without_address', 'reserved_order_id');
        $this->assertNotEmpty($quote->getAllVisibleItems());
        $objectManager->get(CheckoutSession::class)->setQuoteId($quote->getId());

        $objectManager->get(RuleProvider::class)->createRule(AlwaysMatchMatcher::CODE, '*', true);

        $observer = new Observer(['event' => new Event(['quote' => $quote, 'name' => 'sales_quote_save_after'])]);
        $objectManager->get(RemoveQuote::class)->execute($observer);

        $sessionQuote = $objectManager->get(CheckoutSession::class)->getQuote();
        $this->assertEmpty($sessionQuote->getAllVisibleItems());

        $messages = $objectManager->get(MessageManager::class)->getMessages();
        $this->assertSame(1, $messages->getCount());
    }
}
