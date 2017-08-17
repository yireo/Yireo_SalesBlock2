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

namespace Yireo\SalesBlock2\Observer;

/**
 * Class RuleMatch
 *
 * @package Yireo\SalesBlock2\Observer
 */
class RuleMatch implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * RuleMatch constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();

        /** @var \Yireo\SalesBlock2\Api\Data\RuleInterface $rule */
        $rule = $event->getRule();
        $email = (string) $event->getEmail();
        $ip = (string) $event->getIp();

        $message = 'SalesBlock: rule ' . $rule->getId() . ', IP ' . $ip . ', email ' . $email;
        $this->logger->log('notice', $message);
    }
}