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
use Yireo\SalesBlock2\Console\Command\RulesCommand;

/**
 * Class RulesCommandTest
 *
 * @package Yireo\SalesBlock2\Test\Unit\Console\Command
 */
class RulesCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Import traits
     */
    use \Yireo\SalesBlock2\Test\Unit\Mock\RuleRepositoryMock;

    /**
     * @var RulesCommand
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
        $commandTester->execute([]);

        $this->assertContains(
            'No rules found',
            $commandTester->getDisplay()
        );
    }

    /**
     * @return RulesCommand
     */
    private function getTargetCommand()
    {
        $ruleRepository = $this->getRuleRepositoryMock();
        $searchCriteriaBuilder = $this->getSearchCriteriaBuilderMock();

        return new RulesCommand($ruleRepository, $searchCriteriaBuilder);
    }

    /**
     * @return \Magento\Framework\Api\Search\SearchCriteriaBuilder
     */
    protected function getSearchCriteriaBuilderMock()
    {
        /** @var \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder */
        $searchCriteriaBuilder = $this->getMockBuilder(\Magento\Framework\Api\Search\SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        return $searchCriteriaBuilder;
    }
}