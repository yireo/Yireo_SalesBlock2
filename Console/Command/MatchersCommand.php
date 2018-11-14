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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Yireo\SalesBlock2\Matcher\MatcherList;

/**
 * Class MatchersCommand
 *
 * @package Yireo\Example\Console\Command
 */
class MatchersCommand extends Command
{
    /**
     * @var MatcherList
     */
    private $matcherList;

    /**
     * RulesCommand constructor.
     *
     * @param MatcherList $matcherList
     * @param string $name
     */
    public function __construct(
        MatcherList $matcherList,
        $name = null
    ) {
        $this->matcherList = $matcherList;
        return parent::__construct($name);
    }

    /**
     * Configure this command
     */
    protected function configure()
    {
        $description = 'Show all matchers available for creating rules with SalesBlock';
        $this->setName('yireo_salesblock2:matchers')->setDescription($description);
    }

    /**
     * @param Input $input
     * @param Output $output
     *
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        $matchers = $this->matcherList->getMatchers();

        if (empty($matchers)) {
            $suggestion = 'Enable additional SalesBlock matcher extensions first.';
            $output->writeln('No matchers found. '.$suggestion);
            return;
        }

        foreach ($matchers as $matcher) {
            $output->writeln($matcher->getName() . ': ' . $matcher->getDescription());
        }
    }
}