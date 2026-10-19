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

namespace Yireo\SalesBlock2\Test\Unit\Console\Command;

use Magento\Framework\Api\Search\SearchCriteria;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResults;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Yireo\SalesBlock2\Console\Command\RulesCommand;

class RulesCommandTest extends TestCase
{
    public function testOutputsMessageWhenNoRulesFound()
    {
        $commandTester = new CommandTester($this->getCommand([]));
        $exitCode = $commandTester->execute([]);

        $this->assertSame(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('No rules found', $commandTester->getDisplay());
    }

    public function testOutputsAllRules()
    {
        $rules = [
            $this->getRuleMock(1, 'First rule'),
            $this->getRuleMock(2, 'Second rule'),
        ];

        $commandTester = new CommandTester($this->getCommand($rules));
        $exitCode = $commandTester->execute([]);
        $display = $commandTester->getDisplay();

        $this->assertSame(Command::SUCCESS, $exitCode);
        $this->assertStringNotContainsString('No rules found', $display);
        $this->assertStringContainsString('1: First rule', $display);
        $this->assertStringContainsString('2: Second rule', $display);
    }

    /**
     * @param RuleInterface[] $rules
     * @return RulesCommand
     */
    private function getCommand(array $rules): RulesCommand
    {
        $searchResults = new SearchResults();
        $searchResults->setItems($rules);

        $ruleRepository = $this->createMock(RuleRepositoryInterface::class);
        $ruleRepository->method('getList')->willReturn($searchResults);

        $searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $searchCriteriaBuilder->method('create')->willReturn($this->createMock(SearchCriteria::class));

        return new RulesCommand($ruleRepository, $searchCriteriaBuilder);
    }

    private function getRuleMock(int $id, string $label): RuleInterface
    {
        $rule = $this->createMock(RuleInterface::class);
        $rule->method('getId')->willReturn($id);
        $rule->method('getLabel')->willReturn($label);

        return $rule;
    }
}
