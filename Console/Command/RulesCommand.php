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

namespace Yireo\SalesBlock2\Console\Command;

use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;

/**
 * Class RulesCommand
 *
 * @package Yireo\Example\Console\Command
 */
class RulesCommand extends Command
{
    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * RulesCommand constructor.
     *
     * @param RuleRepositoryInterface $ruleRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param string $name
     */
    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        $name = null
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        return parent::__construct($name);
    }

    /**
     * Configure this command
     */
    protected function configure()
    {
        $this->setName('yireo_salesblock2:rules')->setDescription('Show all rules configured in SalesBlock');
    }

    /**
     * @param Input $input
     * @param Output $output
     *
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $rules = $this->ruleRepository->getList($searchCriteria);
        $output->writeln(get_class($this->getApplication()));

        if (empty($rules)) {
            $output->writeln('No rules found');
            return;
        }

        foreach ($rules as $rule) {
            $output->writeln($rule->getId() . ': ' . $rule->getLabel());
        }
    }
}
