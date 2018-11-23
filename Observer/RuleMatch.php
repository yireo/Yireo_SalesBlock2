<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types=1);

namespace Yireo\SalesBlock2\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Match\Match;

/**
 * Class RuleMatch
 *
 * @package Yireo\SalesBlock2\Observer
 */
class RuleMatch implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RuleMatch constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();

        /** @var Match $match */
        $match = $event->getMatch();
        $rule = $match->getRule();
        $conditions = var_export($rule->getConditions(), true);

        $message = 'SalesBlock: rule ' . $rule->getId() . ' = ' . $match->getMessage();
        $this->logger->log('notice', $message);
    }
}
