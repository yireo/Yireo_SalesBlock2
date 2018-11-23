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

use Magento\Framework\Exception\NotFoundException;
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
    public function getMatchers()
    {
        return $this->matchers;
    }

    /**
     * @param string $code
     * @return MatcherInterface
     * @throws NotFoundException
     */
    public function getMatcherByCode(string $code)
    {
        foreach ($this->matchers as $matcher) {
            if ($matcher->getCode() === $code) {
                return $matcher;
            }
        }

        throw new NotFoundException(__('No valid matcher found'));
    }
}
