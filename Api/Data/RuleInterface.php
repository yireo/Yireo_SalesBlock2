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
     * @param string $conditions
     * @return mixed
     */
    public function setConditions(string $conditions);

    /**
     * @return array
     */
    public function getConditions() : array;

    /**
     * @param int $status
     * @return mixed
     */
    public function setStatus(int $status);

    /**
     * @return int
     */
    public function getStatus() : int;

    /**
     * @param string $frontendLabel
     * @return mixed
     */
    public function setFrontendLabel(string $frontendLabel);

    /**
     * @return string
     */
    public function getFrontendLabel() : string;

    /**
     * @param string $frontendText
     * @return mixed
     */
    public function setFrontendText(string $frontendText);

    /**
     * @return string
     */
    public function getFrontendText() : string;
}
