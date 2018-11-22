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
 * Class MatchList
 * @package Yireo\SalesBlock2\Matcher
 */
class MatchList
{
    /**
     * @var Match[]
     */
    private $matches;

    /**
     * @param Match $match
     */
    public function addMatch(Match $match)
    {
        $this->matches[] = $match;
    }

    /**
     * @return Match[]
     */
    public function getMatches(): array
    {
        return $this->matches;
    }
}
