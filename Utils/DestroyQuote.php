<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Utils;

use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Yireo\SalesBlock2\Configuration\Configuration;
use Yireo\SalesBlock2\Logger\Debugger;

class DestroyQuote
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * @var Configuration
     */
    private $configuration;

    private bool $running = false;

    /**
     * DestroyQuote constructor.
     * @param CheckoutSession $checkoutSession
     * @param Cart $cart
     * @param Debugger $debugger
     * @param Configuration $configuration
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        Cart $cart,
        Debugger $debugger,
        Configuration $configuration
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->cart = $cart;
        $this->debugger = $debugger;
        $this->configuration = $configuration;
    }

    /**
     * @return bool
     * @throws LocalizedException
     */
    public function destroy(): bool
    {
        if (!$this->configuration->destroyCart()) {
            return false;
        }

        if ($this->running) {
            return false;
        }

        $this->running = true;
        $this->debugger->debug('Destroying quote');

        try {
            $quote = $this->checkoutSession->getQuote();
            $quote->setItemsCount(0);
            $items = $quote->getAllVisibleItems();
            if ($items) {
                foreach ($items as $item) {
                    $this->cart->removeItem($item->getItemId());
                }
                $this->cart->save();
                $this->checkoutSession->clearStorage();
                $this->checkoutSession->resetCheckout();
            }
        } finally {
            $this->running = false;
        }

        return true;
    }

    public function isRunning(): bool
    {
        return $this->running;
    }
}
