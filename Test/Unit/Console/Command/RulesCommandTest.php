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

use Magento\Framework\Api\Search\SearchCriteria;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use PHPUnit\Framework\TestCase;
use Magento\TestFramework\ObjectManager;
use Symfony\Component\Console\Tester\CommandTester;
use Yireo\SalesBlock2\Console\Command\RulesCommand;
use Yireo\SalesBlock2\Test\Unit\Mock\RuleRepositoryMock;

/**
 * Class RulesCommandTest
 *
 * @package Yireo\SalesBlock2\Test\Unit\Console\Command
 */
class RulesCommandTest extends TestCase
{
    /**
     * Import traits
     */
    use RuleRepositoryMock;

    /**
     * @var RulesCommand
     */
    private $command;

    /**
     * Setup all requirements for the test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->command = $this->getTargetCommand();
    }

    /**
     *
     */
    public function testExecute()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $this->assertTrue((bool)strpos($commandTester->getDisplay(), 'No rules found'));
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
     * @return SearchCriteriaBuilder
     */
    protected function getSearchCriteriaBuilderMock()
    {
        /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
        $searchCriteriaBuilder = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $searchCriteriaBuilder->method('create')
            ->willReturn($this->getSearchCriteriaMock());

        return $searchCriteriaBuilder;
    }

    /**
     * @return SearchCriteria
     */
    private function getSearchCriteriaMock()
    {
        /** @var SearchCriteria $searchCriteria */
        $searchCriteria = $this->getMockBuilder(SearchCriteria::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        return $searchCriteria;
    }
}
