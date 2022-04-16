<?php declare(strict_types=1);

/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Yireo\SalesBlock2\Logger\Debugger;
use Yireo\SalesBlock2\Match\RuleMatch;

/**
 * Class RuleMatch
 *
 * @package Yireo\SalesBlock2\Observer
 */
class MatchRule implements ObserverInterface
{
    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * RuleMatch constructor.
     * @param Debugger $debugger
     */
    public function __construct(
        Debugger $debugger
    ) {
        $this->debugger = $debugger;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();

        /** @var RuleMatch $match */
        $match = $event->getMatch();
        $rule = $match->getRule();

        $message = 'RuleMatch ' . $rule->getId() . ' = ' . $match->getMessage();
        $this->debugger->debug($message);
    }
}
