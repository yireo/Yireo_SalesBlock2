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

namespace Yireo\SalesBlock2\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Class RuleInterface
 *
 * @package Yireo\SalesBlock2\Api\Data
 */
interface RuleInterface extends ExtensibleDataInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $label
     * @return mixed
     */
    public function setLabel(string $label);

    /**
     * @return string
     */
    public function getLabel() : string;

    /**
     * @param string $emailValue
     * @return mixed
     */
    public function setEmailValue(string $emailValue);

    /**
     * @return string
     */
    public function getEmailValue() : string;

    /**
     * @param string $ipValue
     * @return mixed
     */
    public function setIpValue(string $ipValue);

    /**
     * @return string
     */
    public function getIpValue() : string;

    /**
     * @param int $status
     * @return mixed
     */
    public function setStatus(int $status);

    /**
     * @return int
     */
    public function getStatus() : int;

    // @todo: Add other properties
}