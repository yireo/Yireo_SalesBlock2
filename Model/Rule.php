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
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('Yireo\SalesBlock2\Model\ResourceModel\Rule');
    }

    /**
     * @param string $label
     * @return mixed
     */
    public function setLabel(string $label)
    {
        return $this->setData('label', $label);
    }

    /**
     * @return string
     */
    public function getLabel() : string
    {
        return $this->getData('label');
    }

    /**
     * @param string $emailValue
     * @return mixed
     */
    public function setEmailValue(string $emailValue)
    {
        return $this->setData('email_value', $emailValue);
    }

    /**
     * @return string
     */
    public function getEmailValue() : string
    {
        return $this->getData('email_value');
    }

    /**
     * @param string $ipValue
     * @return mixed
     */
    public function setIpValue(string $ipValue)
    {
        return $this->setData('ip_value', $ipValue);
    }

    /**
     * @return string
     */
    public function getIpValue() : string
    {
        return $this->getData('ip_value');
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status)
    {
        return $this->setData('status', $status);
    }

    /**
     * @return int
     */
    public function getStatus() : int
    {
        return $this->getData('status');
    }

    // @todo: Add other properties
}