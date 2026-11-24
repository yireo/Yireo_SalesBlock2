<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Console;

use InvalidArgumentException;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Yireo\SalesBlock2\Console\Command\NewRuleCommand;

class NewRuleCommandTest extends TestCase
{
    /**
     * @magentoDbIsolation enabled
     * @dataProvider getInvalidConditions
     */
    public function testInvalidConditionsAreRejected(string $conditions): void
    {
        $command = Bootstrap::getObjectManager()->create(NewRuleCommand::class);
        $tester = new CommandTester($command);

        $this->expectException(InvalidArgumentException::class);
        $tester->execute(['--label' => 'Test', '--conditions' => $conditions]);
    }

    public static function getInvalidConditions(): array
    {
        return [
            ['{broken'],
            ['[{"name":"unknown_matcher","value":"x"}]'],
            ['[{"name":"x"}]'],
        ];
    }
}
