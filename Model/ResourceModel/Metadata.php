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

namespace Yireo\SalesBlock2\Model\ResourceModel;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class Metadata
 */
class Metadata
{
    /**
     * Reference to rule model
     */
    const RULE_MODEL = '\Yireo\SalesBlock2\Model\Rule';

    /**
     * Reference to rule resource model
     */
    const RULE_RESOURCE_MODEL = '\Yireo\SalesBlock2\Model\ResourceModel\Rule';

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * @return AbstractDb
     */
    public function getMapper(): AbstractDb
    {
        return $this->objectManager->get(self::RULE_RESOURCE_MODEL);
    }

    /**
     * @return ExtensibleDataInterface
     */
    public function getNewInstance(): ExtensibleDataInterface
    {
        return $this->objectManager->create(self::RULE_MODEL);
    }
}
