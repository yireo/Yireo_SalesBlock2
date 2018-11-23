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

namespace Yireo\SalesBlock2\Match;

/**
 * Class MatchHolder
 * @package Yireo\SalesBlock2\Matcher
 */
class MatchHolder
{
    /**
     * @var Match
     */
    private $match;

    /**
     * @param Match $match
     */
    public function setMatch(Match $match)
    {
        $this->match = $match;
    }

    /**
     * @return Match
     */
    public function getMatch(): Match
    {
        return $this->match;
    }
}
