<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Plugin;

use Magento\Framework\Message\ManagerInterface;
use Magento\Checkout\Model\Cart;
use Yireo\SalesBlock2\Exception\RuleMatchedException;
use Yireo\SalesBlock2\Exception\RuleMatchedExceptionFactory;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;

/**
 * Plugin PreventAddToCart
 * @package Yireo\SalesBlock2\Plugin
 */
class PreventAddToCart
{
    /**
     * @var RuleHelper
     */
    private $ruleHelper;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var RuleMatchedExceptionFactory
     */
    private $ruleMatchedExceptionFactory;

    /**
     * PreventAddToCart constructor.
     * @param RuleHelper $ruleHelper
     * @param ManagerInterface $messageManager
     * @param RuleMatchedExceptionFactory $ruleMatchedExceptionFactory
     */
    public function __construct(
        RuleHelper $ruleHelper,
        ManagerInterface $messageManager,
        RuleMatchedExceptionFactory $ruleMatchedExceptionFactory
    ) {
        $this->ruleHelper = $ruleHelper;
        $this->messageManager = $messageManager;
        $this->ruleMatchedExceptionFactory = $ruleMatchedExceptionFactory;
    }

    /**
     * @param Cart $subject
     * @throws RuleMatchedException
     */
    public function beforeSave(
        Cart $subject
    ) {
        if (!$this->hasMatch()) {
            return [];
        }

        $this->giveException();
    }

    /**
     * @throws RuleMatchedException
     */
    private function giveException()
    {
        $exception = $this->getException();
        $this->messageManager->addExceptionMessage($exception);
        throw $exception;
    }

    /**
     * @return RuleMatchedException
     */
    private function getException(): RuleMatchedException
    {
        $message = __('You are not allowed to purchase any products from your IP %s');
        $message = sprintf($message, $this->ruleHelper->getIp());

        return $this->ruleMatchedExceptionFactory->create($message);
    }

    /**
     * @return bool
     */
    private function hasMatch(): bool
    {
        return $this->ruleHelper->hasMatch();
    }
}