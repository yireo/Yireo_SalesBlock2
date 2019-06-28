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

use Error;
use InvalidArgumentException;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class NewRuleCommand
 *
 * @package Yireo\Example\Console\Command
 */
class NewRuleCommand extends Command
{
    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * NewRuleCommand constructor.
     *
     * @param RuleRepositoryInterface $ruleRepository
     * @param string $name
     */
    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        $name = null
    ) {
        $this->ruleRepository = $ruleRepository;

        return parent::__construct($name);
    }

    /**
     * Configure this command
     */
    protected function configure()
    {
        $this->setName('yireo_salesblock2:rule:new');
        $this->setDescription('Create a new SalesBlock2 rule');

        $this->addOption(
            'label',
            null,
            InputOption::VALUE_REQUIRED,
            'Rule label'
        );

        // @todo: Rewrite this to an interactive way of asking for the right matcher
        $this->addOption(
            'conditions',
            null,
            InputOption::VALUE_REQUIRED,
            'Conditions'
        );
    }

    /**
     * @param Input $input
     * @param Output $output
     *
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        try {
            $label = trim($input->getOption('label'));
            $conditions = trim($input->getOption('conditions'));
        } catch (Error $e) {
            throw new InvalidArgumentException('Unable to initialize options');
        }

        if (empty($label)) {
            throw new InvalidArgumentException('Option "label" is missing');
        }

        if (empty($conditions)) {
            throw new InvalidArgumentException('Conditions are required');
        }

        $this->createRule($label, $conditions);
        $output->writeln('<info>Rule has been created</info>');
    }

    /**
     * @param string $label
     * @param string $conditions
     */
    protected function createRule(string $label, string $conditions)
    {
        $rule = $this->ruleRepository->create();
        $rule->setLabel($label);
        $rule->setConditions($conditions);

        $this->ruleRepository->save($rule);
    }
}
