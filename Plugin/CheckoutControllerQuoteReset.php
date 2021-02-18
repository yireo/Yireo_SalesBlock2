<?php declare(strict_types=1);
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Plugin;

use Magento\Checkout\Controller\Onepage as OnepageController;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;
use Yireo\SalesBlock2\Logger\Debugger;
use Yireo\SalesBlock2\Utils\DestroyQuote;

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
     * @var RedirectFactory
     */
    private $resultRedirectFactory;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var DestroyQuote
     */
    private $destroyQuote;
    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * PreventAddToCart constructor.
     * @param RuleHelper $ruleHelper
     * @param RedirectFactory $resultRedirectFactory
     * @param ManagerInterface $messageManager
     * @param DestroyQuote $destroyQuote
     * @param Debugger $debugger
     */
    public function __construct(
        RuleHelper $ruleHelper,
        RedirectFactory $resultRedirectFactory,
        ManagerInterface $messageManager,
        DestroyQuote $destroyQuote,
        Debugger $debugger
    ) {
        $this->ruleHelper = $ruleHelper;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
        $this->destroyQuote = $destroyQuote;
        $this->debugger = $debugger;
    }

    /**
     * @param OnepageController $subject
     * @param ResultInterface $result
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function afterExecute(
        OnepageController $subject,
        ResultInterface $result
    ): ResultInterface {
        try {
            $match = $this->ruleHelper->findMatch();
            $this->messageManager->addWarningMessage($match->getMessage());
            $this->destroyQuote->destroy();
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setUrl('/checkout/cart');
            return $resultRedirect;
        } catch (NotFoundException $exception) {
            $this->debugger->debug('Plugin for Magento\Checkout\Controller\Onepage: No match found');
            return $result;
        }
    }
}
