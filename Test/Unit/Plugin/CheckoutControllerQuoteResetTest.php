<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Unit\Plugin;

use Magento\Checkout\Controller\Onepage;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;
use Yireo\SalesBlock2\Logger\Debugger;
use Yireo\SalesBlock2\Plugin\CheckoutControllerQuoteReset;
use Yireo\SalesBlock2\RuleMatch\RuleMatch;
use Yireo\SalesBlock2\Utils\DestroyQuote;

class CheckoutControllerQuoteResetTest extends TestCase
{
    public function testRedirectUsesStoreAwarePath(): void
    {
        $match = $this->createMock(RuleMatch::class);
        $match->method('getMessage')->willReturn('Blocked');

        $ruleHelper = $this->createMock(RuleHelper::class);
        $ruleHelper->method('findMatch')->willReturn($match);

        $redirect = $this->createMock(Redirect::class);
        $redirect->expects($this->once())->method('setPath')->with('checkout/cart')->willReturnSelf();
        $redirect->expects($this->never())->method('setUrl');

        $redirectFactory = $this->createMock(RedirectFactory::class);
        $redirectFactory->method('create')->willReturn($redirect);

        $plugin = new CheckoutControllerQuoteReset(
            $ruleHelper,
            $redirectFactory,
            $this->createMock(ManagerInterface::class),
            $this->createMock(DestroyQuote::class),
            $this->createMock(Debugger::class)
        );

        $result = $plugin->afterExecute(
            $this->createMock(Onepage::class),
            $this->createMock(ResultInterface::class)
        );

        $this->assertSame($redirect, $result);
    }
}
