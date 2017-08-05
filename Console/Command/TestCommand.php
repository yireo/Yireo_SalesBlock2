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
namespace Yireo\SalesBlock2\Console\Command;

use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;

/**
 * Class TestCommand
 *
 * @package Yireo\Example\Console\Command
 */
class TestCommand extends Command
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
	 * TestCommand constructor.
	 *
	 * @param RuleRepositoryInterface $ruleRepository
	 * @param SearchCriteriaBuilder   $searchCriteriaBuilder
	 * @param string                  $name
	 */
    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
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
        $this->setName('yireo_salesblock2:test')->setDescription('Test whether SalesBlock2 rules apply to given data');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        $output->writeln('Not implemented yet');
    }
}