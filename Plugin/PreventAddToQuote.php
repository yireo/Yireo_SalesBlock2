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

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Api\Data\CartInterface;
use Yireo\SalesBlock2\Exception\RuleMatchedException;
use Yireo\SalesBlock2\Exception\RuleMatchedExceptionFactory;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;

/**
 * Plugin PreventAddToQuote
 * @package Yireo\SalesBlock2\Plugin
 */
class PreventAddToQuote
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
     * @param CartInterface $subject
     * @param Product $product
     * @param null $request
     * @param string $processMode
     * @return array
     * @throws RuleMatchedException
     */
    public function beforeAddProduct(
        CartInterface $subject,
        Product $product,
        $request = null,
        $processMode = AbstractType::PROCESS_MODE_FULL
    ) {
        if (!$this->ruleHelper->hasMatch()) {
            return [$product, $request, $processMode];
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
        $message = sprintf($message, $this->ruleHelper->getCurrentIp());

        return $this->ruleMatchedExceptionFactory->create($message);
    }
}
