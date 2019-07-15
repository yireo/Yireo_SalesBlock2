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

namespace Yireo\SalesBlock2\Helper;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\NotFoundException;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Configuration\Configuration;
use Yireo\SalesBlock2\Exception\NoMatchException;
use Yireo\SalesBlock2\Match\Match;
use Yireo\SalesBlock2\Matcher\MatcherList;
use Yireo\SalesBlock2\Model\Rule\Service as RuleService;

/**
 * Class Rule
 *
 * @package Yireo\SalesBlock2\Helper
 * @todo Move this to dedicated class
 */
class Rule
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @var RuleService
     */
    private $ruleService;

    /**
     * @var ManagerInterface
     */
    private $eventManager;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var MatcherList
     */
    private $matcherList;

    /**
     * Rule constructor.
     *
     * @param MatcherList $matcherList
     * @param Configuration $configuration
     * @param Data $moduleHelper
     * @param RuleService $ruleService
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        MatcherList $matcherList,
        Configuration $configuration,
        Data $moduleHelper,
        RuleService $ruleService,
        ManagerInterface $eventManager
    ) {
        $this->matcherList = $matcherList;
        $this->configuration = $configuration;
        $this->helper = $moduleHelper;
        $this->ruleService = $ruleService;
        $this->eventManager = $eventManager;
    }

    /**
     * @return RuleInterface[]
     */
    public function getRules()
    {
        return $this->ruleService->getRules();
    }

    /**
     * Method to check whether the current visitor matches a SalesBlock rule
     *
     * @return Match
     * @throws NotFoundException
     */
    public function findMatch()
    {
        // Check whether the module is disabled
        if ($this->configuration->enabled() === false) {
            throw new NotFoundException(__('SalesBlock is not enabled'));
        }

        // Load all rules and exit if there are no rules
        $rules = $this->ruleService->getRules();
        if (empty($rules)) {
            throw new NotFoundException(__('No rules are found'));
        }

        // Loop through all rules
        foreach ($rules as $rule) {
            if ($match = $this->processRuleWithMatch($rule)) {
                return $match;
            }
        }

        throw new NotFoundException(__('No rule is applicable'));
    }

    /**
     * @param RuleInterface $rule
     * @return bool|Match
     */
    private function processRuleWithMatch(RuleInterface $rule)
    {
        try {
            if ($match = $this->getMatchFromRule($rule)) {
                $this->afterMatch($match);
                return $match;
            }
        } catch (NotFoundException $exception) {
            return false;
        }
    }

    /**
     * @param RuleInterface $rule
     *
     * @return Match
     * @throws NotFoundException
     */
    private function getMatchFromRule(RuleInterface $rule): Match
    {
        $conditions = $rule->getConditions();
        foreach ($conditions as $condition) {
            if ($match = $this->findMatchFromCondition($condition)) {
                $match->setRule($rule);
                return $match;
            }
        }

        throw new NotFoundException(__('This rule is not applicable'));
    }

    /**
     * @param array $condition
     * @return bool|Match
     */
    private function findMatchFromCondition(array $condition)
    {
        if (!isset($condition['name'])) {
            return false;
        }

        try {
            $matcher = $this->matcherList->getMatcherByCode($condition['name']);
        } catch (NotFoundException $exception) {
            return false;
        }

        try {
            if (!$match = $matcher->match($condition['value'])) {
                return false;
            }
        } catch (NoMatchException $exception) {
            return false;
        }

        return $match;
    }

    /**
     * Method to execute when a visitor is actually matched
     *
     * @param Match $match
     */
    private function afterMatch(Match $match)
    {
        $eventArguments = ['match' => $match];
        $this->eventManager->dispatch('salesblock_rule_match_after', $eventArguments);
    }
}
