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
namespace Yireo\SalesBlock2\Test\Console\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Yireo\SalesBlock2\Console\Command\NewRuleCommand;

class NewRuleCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NewRuleCommand
     */
    protected $command = '';

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
		return;
        $commandTester = new CommandTester($this->command);


        $commandTester->execute([]);

        $this->assertContains(
            'InvalidArgumentException',
            $commandTester->getDisplay()
        );
    }

    protected function getTargetCommand()
    {
        $ruleRepository = $this->getMockBuilder(RuleRepositoryInterface::class)
            ->getMockForAbstractClass();

        return new NewRuleCommand($ruleRepository);
    }

    protected function getApplicationMock()
    {
        $application = $this->getMock('Symfony\Component\Console\Application');
        $application->expects($this->any())
            ->with(NewRuleCommand::class)
            ->method('add');

        return $application;
    }
}