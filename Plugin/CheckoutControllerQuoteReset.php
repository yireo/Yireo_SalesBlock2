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

use Magento\Checkout\Controller\Onepage as OnepageController;
use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;

/**
 * Plugin ControllerQuoteReset
 * @package Yireo\SalesBlock2\Plugin
 */
class CheckoutControllerQuoteReset
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
     * @var RedirectFactory
     */
    private $resultRedirectFactory;

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
     * @param RedirectFactory $resultRedirectFactory
     * @param ManagerInterface $messageManager
     * @param Cart $cart
     */
    public function __construct(
        RuleHelper $ruleHelper,
        CheckoutSession $checkoutSession,
        RedirectFactory $resultRedirectFactory,
        ManagerInterface $messageManager,
        Cart $cart
    ) {
        $this->ruleHelper = $ruleHelper;
        $this->checkoutSession = $checkoutSession;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
        $this->cart = $cart;
    }

    /**
     * @param OnepageController $subject
     * @param ResultInterface $result
     * @return ResultInterface
     */
    public function afterExecute(
        OnepageController $subject,
        ResultInterface $result
    ): ResultInterface {
        try {
            $match = $this->ruleHelper->findMatch();
            $this->messageManager->addWarningMessage($match->getMessage());

            $quote = $this->checkoutSession->getQuote();
            foreach ($quote->getAllVisibleItems() as $item) {
                $itemId = $item->getItemId();
                $this->cart->removeItem($itemId)->save();
            }
            $this->checkoutSession->clearStorage();
            $this->checkoutSession->resetCheckout();

            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setUrl('/checkout/cart');
            return $resultRedirect;
        } catch (NotFoundException $exception) {
            return $result;
        }
    }
}
