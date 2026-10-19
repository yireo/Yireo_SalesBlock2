<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Stub;

use Yireo\SalesBlock2\Api\MatcherInterface;
use Yireo\SalesBlock2\RuleMatch\RuleMatch;

class AlwaysMatchMatcher implements MatcherInterface
{
    public const CODE = 'test_always';

    public function getCode(): string
    {
        return self::CODE;
    }

    public function getName(): string
    {
        return 'Test matcher (always matches)';
    }

    public function getDescription(): string
    {
        return 'Matcher that always matches, used in integration tests';
    }

    public function match(string $value): RuleMatch
    {
        return new RuleMatch('Test match');
    }
}
