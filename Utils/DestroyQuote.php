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

        $this->debugger->debug('Destroying quote');

        $quote = $this->checkoutSession->getQuote();
        $quote->setItemsCount(0);
        if ($quote->getAllVisibleItems()) {
            foreach ($quote->getAllVisibleItems() as $item) {
                $itemId = $item->getItemId();
                $this->cart->removeItem($itemId)->save();
            }
            $this->checkoutSession->clearStorage();
            $this->checkoutSession->resetCheckout();
        }

        return true;
    }
}
