<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types=1);

namespace Yireo\SalesBlock2\Plugin;

use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Checkout\Controller\Cart\Index as CartController;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;

/**
 * Plugin CartControllerQuoteReset
 * @package Yireo\SalesBlock2\Plugin
 */
class CartControllerQuoteReset
{
    /**
     * @var RuleHelper
     */
    private $ruleHelper;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var Cart
     */
    private $cart;

    /**
     * PreventAddToCart constructor.
     * @param RuleHelper $ruleHelper
     * @param CheckoutSession $checkoutSession
     * @param ManagerInterface $messageManager
     * @param Cart $cart
     */
    public function __construct(
        RuleHelper $ruleHelper,
        CheckoutSession $checkoutSession,
        ManagerInterface $messageManager,
        Cart $cart
    ) {
        $this->ruleHelper = $ruleHelper;
        $this->checkoutSession = $checkoutSession;
        $this->messageManager = $messageManager;
        $this->cart = $cart;
    }

    /**
     * @param CartController $subject
     */
    public function beforeExecute(
        CartController $subject
    ) {
        try {
            $match = $this->ruleHelper->findMatch();
            $this->messageManager->addWarningMessage($match->getMessage());

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
            return;
        } catch (NotFoundException $exception) {
            return;
        }
    }
}
