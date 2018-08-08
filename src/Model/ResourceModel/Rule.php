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

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Rule
 *
 * @package Yireo\SalesBlock2\Model\ResourceModel
 */
class Rule extends AbstractDb
{
    /**
     * Initialize database
     */
    protected function _construct()
    {
        $this->_init('salesblock_rule', 'rule_id');
    }
}