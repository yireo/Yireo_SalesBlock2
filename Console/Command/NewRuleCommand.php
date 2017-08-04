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

use Yireo\SalesBlock2\Api\RuleRepositoryInterface;

use Symfony\Component\Console\Exception\InvalidArgumentException;
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

    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        string $name = null
    )
    {
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
            'Rule label');

        $this->addOption(
            'email_value',
            null,
            InputOption::VALUE_OPTIONAL,
            'Email value');

        $this->addOption(
            'ip_value',
            null,
            InputOption::VALUE_OPTIONAL,
            'IP value');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        $label = trim($input->getOption('label'));
        $ipValue = trim($input->getOption('ip_value'));
        $emailValue = trim($input->getOption('email_value'));

        if (empty($label)) {
            throw new InvalidArgumentException('Option "label" is missing');
        }

        if (empty($ipValue) && empty($emailValue)) {
            throw new InvalidArgumentException('Either option "ip_value" or "email_value" is required');
        }

        $this->createRule($label, $ipValue, $emailValue);
        $output->writeln('<info>Rule has been created</info>');
    }

    /**
     * @param string $label
     * @param string $ipValue
     * @param string $emailValue
     */
    protected function createRule(string $label, string $ipValue, string $emailValue)
    {
        $rule = $this->ruleRepository->create();
        $rule->setLabel($label);
        $rule->setIpValue($ipValue);
        $rule->setEmailValue($emailValue);
        $this->ruleRepository->save($rule);
    }
}