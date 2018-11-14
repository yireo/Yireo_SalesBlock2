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

        /** @var RuleInterface $rule */
        $rule = $event->getRule();
        $conditions = (string) $rule->getConditions();

        $message = 'SalesBlock: rule ' . $rule->getId() . ' = ' . $conditions;
        $this->logger->log('notice', $message);
    }
}
