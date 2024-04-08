<?php declare(strict_types=1);

/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Api;

use Yireo\SalesBlock2\RuleMatch\RuleMatch;

/**
 * Class MatcherInterface
 *
 * @package Yireo\SalesBlock2\Api\Data
 */
interface MatcherInterface
{
    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $value
     *
     * @return RuleMatch
     */
    public function match(string $value): RuleMatch;
}
