<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types = 1);

namespace Yireo\SalesBlock2\Test\Unit\Console\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Yireo\SalesBlock2\Console\Command\DeleteRuleCommand;

/**
 * Class DeleteRuleCommandTest
 *
 * @package Yireo\SalesBlock2\Test\Unit\Console\Command
 */
class DeleteRuleCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Import traits
     */
    use \Yireo\SalesBlock2\Test\Unit\Mock\RuleRepositoryMock;

    /**
     * @var DeleteRuleCommand
     */
    private $command;

    /**
     * Setup all requirements for the test
     */
    protected function setUp()
    {
        $this->command = $this->getTargetCommand();
    }

    /**
     *
     */
    public function testExecute()
    {
        $commandTester = new CommandTester($this->command);

        $options = ['--id' => '1'];
        $commandTester->execute($options);

        $this->assertContains(
            'Rule has been deleted',
            $commandTester->getDisplay()
        );
    }

    /**
     * @expectedException
     */
    public function testExecuteWithInvalidArguments()
    {
        $this->expectException(\InvalidArgumentException::class);

        $commandTester = new CommandTester($this->command);
        $options = [];
        $commandTester->execute($options);
    }

    /**
     * @return DeleteRuleCommand
     */
    private function getTargetCommand()
    {
        /** @var \Yireo\SalesBlock2\Api\RuleRepositoryInterface $ruleRepository */
        $ruleRepository = $this->getRuleRepositoryMock();

        return new DeleteRuleCommand($ruleRepository);
    }
}