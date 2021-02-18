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

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Checkout\Controller\Cart\Index as CartController;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;
use Yireo\SalesBlock2\Logger\Debugger;
use Yireo\SalesBlock2\Utils\DestroyQuote;

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
     * @param ManagerInterface $messageManager
     * @param DestroyQuote $destroyQuote
     * @param Debugger $debugger
     */
    public function __construct(
        RuleHelper $ruleHelper,
        ManagerInterface $messageManager,
        DestroyQuote $destroyQuote,
        Debugger $debugger
    ) {
        $this->ruleHelper = $ruleHelper;
        $this->messageManager = $messageManager;
        $this->destroyQuote = $destroyQuote;
        $this->debugger = $debugger;
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
            $this->destroyQuote->destroy();
            return;
        } catch (NotFoundException $exception) {
            $this->debugger->debug('Plugin for Magento\Checkout\Controller\Cart\Index: No match found');
            return;
        }
    }
}
