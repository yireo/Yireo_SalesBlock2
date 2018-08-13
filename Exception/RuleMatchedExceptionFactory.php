<?php

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
