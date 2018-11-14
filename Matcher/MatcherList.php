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

namespace Yireo\SalesBlock2\Matcher;

use Yireo\SalesBlock2\Api\Data\MatcherInterface;

/**
 * Class MatcherList
 * @package Yireo\SalesBlock2\Matcher
 */
class MatcherList
{
    /**
     * @var MatcherInterface[]
     */
    private $matchers;

    /**
     * MatcherList constructor.
     * @param MatcherInterface[] $matchers
     */
    public function __construct(
        $matchers = []
    ) {
        $this->matchers = $matchers;
    }

    /**
     * @return MatcherInterface[]
     */
    public function getMatchers(): array
    {
        return $this->matchers;
    }
}
