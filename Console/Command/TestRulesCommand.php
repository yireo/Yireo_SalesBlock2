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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;

/**
 * Class TestRulesCommand
 *
 * @package Yireo\Example\Console\Command
 */
class TestRulesCommand extends Command
{
    /**
     * @var
     */
    private $ruleHelper;

    /**
     * TestRulesCommand constructor.
     * @param RuleHelper $ruleHelper
     * @param null $name
     */
    public function __construct(
        RuleHelper $ruleHelper,
        $name = null
    ) {
        $this->ruleHelper = $ruleHelper;

        return parent::__construct($name);
    }

    /**
     * Configure this command
     */
    protected function configure()
    {
        $this->setName('yireo_salesblock2:test')->setDescription('Test all rules for specific matches');
        $this->setDefinition([
            new InputOption(
                'conditions',
                null,
                InputOption::VALUE_OPTIONAL,
                'Conditions'
            )
        ]);
    }

    /**
     * @param Input $input
     * @param Output $output
     *
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
    }
}