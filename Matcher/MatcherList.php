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

use Yireo\SalesBlock2\Api\MatcherInterface;

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

    /**
     * @param string $code
     * @return MatcherInterface
     * @throws \Exception
     */
    public function getMatcherByCode(string $code): MatcherInterface
    {
        foreach ($this->matchers as $matcher) {
            if ($matcher->getCode() === $code) {
                return $matcher;
            }
        }

        throw new \Exception('No valid matcher found');
    }
}
