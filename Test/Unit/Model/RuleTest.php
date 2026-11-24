<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Model\Rule;

class RuleTest extends TestCase
{
    /**
     * @dataProvider getMalformedConditions
     */
    public function testMalformedConditionsReturnEmptyArray(string $conditions): void
    {
        $this->assertSame([], $this->createRule($conditions)->getConditions());
    }

    public static function getMalformedConditions(): array
    {
        return [[''], ['{broken'], ['"string"'], ['42'], ['null']];
    }

    public function testNonArrayConditionsAreSkipped(): void
    {
        $rule = $this->createRule('[{"name":"email","value":"a@b.c"},"foo",3]');
        $this->assertSame([['name' => 'email', 'value' => 'a@b.c']], $rule->getConditions());
    }

    private function createRule(string $conditions): Rule
    {
        $rule = $this->getMockBuilder(Rule::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
        $rule->setData('conditions', $conditions);
        return $rule;
    }
}
