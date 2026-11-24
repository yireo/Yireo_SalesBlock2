<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Frontend;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Model\Quote;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Utils\CurrentEmail;

class CurrentEmailTest extends TestCase
{
    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     */
    public function testGuestEmailFromQuote(): void
    {
        $quote = $this->createQuote();
        $quote->setCustomerEmail('guest@example.com');

        $this->assertSame('guest@example.com', $this->getCurrentEmail($quote)->getValue());
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     */
    public function testGuestEmailFromBillingAddress(): void
    {
        $quote = $this->createQuote();
        $quote->getBillingAddress()->setEmail(' billing@example.com ');

        $this->assertSame('billing@example.com', $this->getCurrentEmail($quote)->getValue());
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     */
    public function testEmptyResultIsNotCached(): void
    {
        $quote = $this->createQuote();
        $currentEmail = $this->getCurrentEmail($quote);
        $this->assertSame('', $currentEmail->getValue());

        $quote->setCustomerEmail('late@example.com');
        $this->assertSame('late@example.com', $currentEmail->getValue());
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     */
    public function testExplicitValueWins(): void
    {
        $quote = $this->createQuote();
        $quote->setCustomerEmail('guest@example.com');

        $currentEmail = $this->getCurrentEmail($quote);
        $currentEmail->setValue('order@example.com');
        $this->assertSame('order@example.com', $currentEmail->getValue());
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     */
    public function testResolvedEmailIsNotCached(): void
    {
        $quote = $this->createQuote();
        $quote->setCustomerEmail('first@example.com');
        $currentEmail = $this->getCurrentEmail($quote);
        $this->assertSame('first@example.com', $currentEmail->getValue());

        $quote->setCustomerEmail('second@example.com');
        $this->assertSame('second@example.com', $currentEmail->getValue());
    }

    private function createQuote(): Quote
    {
        return Bootstrap::getObjectManager()->create(Quote::class);
    }

    private function getCurrentEmail(Quote $quote): CurrentEmail
    {
        $checkoutSession = $this->createMock(CheckoutSession::class);
        $checkoutSession->method('getQuote')->willReturn($quote);

        return Bootstrap::getObjectManager()->create(CurrentEmail::class, [
            'checkoutSession' => $checkoutSession,
        ]);
    }
}
