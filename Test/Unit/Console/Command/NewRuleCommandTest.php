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

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Yireo\SalesBlock2\Console\Command\NewRuleCommand;
use Yireo\SalesBlock2\Test\Unit\Mock\RuleRepositoryMock;

/**
 * Class NewRuleCommandTest
 *
 * @package Yireo\SalesBlock2\Test\Unit\Console\Command
 */
class NewRuleCommandTest extends TestCase
{
    /**
     * Import traits
     */
    use RuleRepositoryMock;

    /**
     * @var NewRuleCommand
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

        $options = ['--label' => 'test', '--email-value' => '@example.com', '--ip-value' => ''];
        $commandTester->execute($options);

        $this->assertContains(
            'Rule has been created',
            $commandTester->getDisplay()
        );
    }

    /**
     * @expectedException
     */
    public function testExecuteWithInvalidArguments()
    {
        $this->expectException(InvalidArgumentException::class);

        $commandTester = new CommandTester($this->command);
        $options = [];
        $commandTester->execute($options);
    }

    /**
     * @return NewRuleCommand
     */
    private function getTargetCommand()
    {
        /** @var RuleRepositoryInterface $ruleRepository */
        $ruleRepository = $this->getRuleRepositoryMock();

        return new NewRuleCommand($ruleRepository);
    }
}