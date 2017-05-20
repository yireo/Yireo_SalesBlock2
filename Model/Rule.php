<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Model;

use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Rule
 *
 * @package Yireo\SalesBlock2\Model
 */
class Rule extends AbstractModel implements RuleInterface
{
    protected function _construct()
    {
        $this->_init('Yireo\SalesBlock2\Model\ResourceModel\Rule');
    }
}