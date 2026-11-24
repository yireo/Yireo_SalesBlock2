<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Unit\RuleMatch;

use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\RuleMatch\RuleMatch;

class RuleMatchTest extends TestCase
{
    public function testVariablesAreStrippedFromControlCharacters(): void
    {
        $rule = $this->createMock(RuleInterface::class);
        $rule->method('getFrontendLabel')->willReturn('Blocked: %email%');

        $match = new RuleMatch('Fallback');
        $match->setRule($rule);
        $match->setVariables(['email' => "evil@example.org\r\nInjected"]);

        $this->assertSame('Blocked: evil@example.org Injected', $match->getMessage());
    }
}
