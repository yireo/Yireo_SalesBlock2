<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Model\ResourceModel;

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
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    public function getMapper()
    {
        return $this->objectManager->get(self::RULE_RESOURCE_MODEL);
    }

    /**
     * @return \Magento\Framework\Api\ExtensibleDataInterface
     */
    public function getNewInstance()
    {
        return $this->objectManager->create(self::RULE_MODEL);
    }
}
