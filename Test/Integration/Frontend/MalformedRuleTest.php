<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Frontend;

use Magento\Framework\Exception\NotFoundException;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;
use Yireo\SalesBlock2\Matcher\MatcherList;
use Yireo\SalesBlock2\Test\Integration\Stub\AlwaysMatchMatcher;

class MalformedRuleTest extends TestCase
{
    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoConfigFixture current_store salesblock/settings/enabled 1
     * @dataProvider getMalformedConditions
     */
    public function testMalformedRuleDoesNotBreakMatching(string $conditions): void
    {
        $objectManager = Bootstrap::getObjectManager();
        $objectManager->configure([
            MatcherList::class => [
                'arguments' => [
                    'matchers' => [
                        'test_always' => ['instance' => AlwaysMatchMatcher::class],
                    ],
                ],
            ],
        ]);

        $ruleRepository = $objectManager->get(RuleRepositoryInterface::class);
        $rule = $ruleRepository->create();
        $rule->setLabel('Malformed');
        $rule->setStatus(1);
        $rule->setConditions($conditions);
        $ruleRepository->save($rule);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('No rule is applicable');
        $objectManager->create(RuleHelper::class)->findMatch();
    }

    public static function getMalformedConditions(): array
    {
        return [
            ['{broken'],
            ['"string"'],
            ['[{"name":"test_always"}]'],
            ['[{"name":"test_always","value":["array"]}]'],
            ['[{"name":["test_always"],"value":"x"}]'],
        ];
    }
}
