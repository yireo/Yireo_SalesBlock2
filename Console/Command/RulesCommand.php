<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;

/**
 * Class RulesCommand
 *
 * @package Yireo\Example\Console\Command
 */
class RulesCommand extends Command
{
    /**
     * @var \Yireo\SalesBlock2\Api\RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var \Magento\Framework\Api\Search\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * RulesCommand constructor.
     *
     * @param \Yireo\SalesBlock2\Api\RuleRepositoryInterface $ruleRepository
     * @param \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param null $name
     */
    public function __construct(
        \Yireo\SalesBlock2\Api\RuleRepositoryInterface $ruleRepository,
        \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder,
        $name = null
    )
    {
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
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $rules = $this->ruleRepository->getList($searchCriteria);
        $output->writeln(get_class($this->getApplication()));

        foreach ($rules as $rule) {
            $output->writeln($rule->getId() . ': ' . $rule->getLabel());
        }
    }
}