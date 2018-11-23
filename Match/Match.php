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

use Yireo\SalesBlock2\Api\Data\RuleInterface;

/**
 * Class Match
 * @package Yireo\SalesBlock2\Matcher
 */
class Match
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * Match constructor.
     * @param string $message
     */
    public function __construct(
        string $message = ''
    ) {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return RuleInterface
     */
    public function getRule(): RuleInterface
    {
        return $this->rule;
    }

    /**
     * @param RuleInterface $rule
     * @return Match
     */
    public function setRule(RuleInterface $rule): Match
    {
        $this->rule = $rule;
        return $this;
    }
}
