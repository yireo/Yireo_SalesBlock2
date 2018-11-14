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

namespace Yireo\SalesBlock2\Exception;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Phrase;

/**
 * Factory class for @see \Yireo\SalesBlock2\Exception\RuleMatchedException
 */
class RuleMatchedExceptionFactory
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Factory constructor
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Yireo\SalesBlock2\Exception\RuleMatchedException
     */
    public function create(string $message)
    {
        $data['phrase'] = new Phrase($message);

        return $this->objectManager->create(RuleMatchedException::class, $data);
    }
}
