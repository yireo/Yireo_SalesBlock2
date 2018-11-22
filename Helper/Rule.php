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

use Exception;
use Magento\Framework\Event\ManagerInterface;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Configuration\Configuration;
use Yireo\SalesBlock2\Match\Match;
use Yireo\SalesBlock2\Match\MatchList;
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
     * @var MatchList
     */
    private $matchList;

    /**
     * Rule constructor.
     *
     * @param MatcherList $matcherList
     * @param Configuration $configuration
     * @param Data $moduleHelper
     * @param RuleService $ruleService
     * @param ManagerInterface $eventManager
     * @param MatchList $matchList
     */
    public function __construct(
        MatcherList $matcherList,
        Configuration $configuration,
        Data $moduleHelper,
        RuleService $ruleService,
        ManagerInterface $eventManager,
        MatchList $matchList
    ) {
        $this->matcherList = $matcherList;
        $this->configuration = $configuration;
        $this->helper = $moduleHelper;
        $this->ruleService = $ruleService;
        $this->eventManager = $eventManager;
        $this->matchList = $matchList;
    }

    /**
     * Method to check whether the current visitor matches a SalesBlock rule
     *
     * @return bool
     */
    public function hasMatch()
    {
        return (bool)$this->findMatch();
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->ruleService->getRules();
    }

    /**
     * Method to check whether the current visitor matches a SalesBlock rule
     *
     * @return int
     */
    public function findMatch(): int
    {
        // Check whether the module is disabled
        if ($this->configuration->enabled() === false) {
            return 0;
        }

        // Load all rules and exit if there are no rules
        $rules = $this->ruleService->getRules();

        if (count($rules) === false) {
            return 0;
        }

        // Loop through all rules
        foreach ($rules as $rule) {
            if ($matchId = $this->getMatchIdFromRule($rule)) {
                return $matchId;
            }
        }

        return 0;
    }

    /**
     * @param RuleInterface $rule
     *
     * @return int
     */
    private function getMatchIdFromRule(RuleInterface $rule): int
    {
        $conditions = $rule->getConditions();
        foreach ($conditions as $condition) {
            if (!isset($condition['name'])) {
                continue;
            }

            try {
                $matcher = $this->matcherList->getMatcherByCode($condition['name']);
            } catch (Exception $e) {
                continue;
            }

            if ($matcher->match($condition['value'])) {
                $matches = $this->matchList->getMatches();
                $this->afterMatch($rule, array_shift($matches));
                return $rule->getId();
            }
        }

        return 0;
    }

    /**
     * Method to execute when a visitor is actually matched
     *
     * @param RuleInterface $rule
     * @param Match $match
     */
    public function afterMatch(RuleInterface $rule, Match $match)
    {
        $eventArguments = ['rule' => $rule, 'match' => $match];
        $this->eventManager->dispatch('salesblock_rule_match_after', $eventArguments);
    }
}
