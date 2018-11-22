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
}
