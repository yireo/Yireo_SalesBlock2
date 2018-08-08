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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class DeleteRuleCommand
 *
 * @package Yireo\Example\Console\Command
 */
class DeleteRuleCommand extends Command
{
    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * DeleteRuleCommand constructor.
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
        $this->setName('yireo_salesblock2:rule:delete');
        $this->setDescription('Delete an existing SalesBlock2 rule');

        $this->addOption(
            'id',
            null,
            InputOption::VALUE_REQUIRED,
            'Rule ID');
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
            $ruleId = (int)$input->getOption('id');
        } catch (\Error $e) {
            throw new \InvalidArgumentException('Unable to initialize options');
        }

        if (empty($ruleId)) {
            throw new \InvalidArgumentException('Option "id" is missing');
        }

        $this->deleteRule($ruleId);
        $output->writeln('<info>Rule has been deleted</info>');
    }

    /**
     * @param int $ruleId
     */
    protected function deleteRule(int $ruleId)
    {
        $rule = $this->ruleRepository->get($ruleId);
        $this->ruleRepository->delete($rule);
    }
}