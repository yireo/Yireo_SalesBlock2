<?php declare(strict_types=1);

/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Model\Rule;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;

/**
 * Class Service
 *
 * @package Yireo\SalesBlock2\Model\Rule
 * @todo Move this to Yireo\SalesBlock2\Service\RuleService
 */
class Service
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
     * @var RuleInterface[]
     */
    private $rules;

    /**
     * Service constructor.
     *
     * @param RuleRepositoryInterface $ruleRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Helper-method to fetch all rules
     *
     * @return RuleInterface[]
     */
    public function getRules(): array
    {
        if (is_array($this->rules)) {
            return $this->rules;
        }

        $searchCriteriaBuilder = $this->searchCriteriaBuilder;
        $searchCriteriaBuilder->addFilter($this->getActiveFilter());
        $searchCriteria = $searchCriteriaBuilder->create();
        $this->rules = $this->ruleRepository->getItems($searchCriteria);
        return $this->rules;
    }

    /**
     * @param RuleInterface $rule
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * @return Service
     */
    public function reset(): Service
    {
        $this->rules = [];
        return $this;
    }

    /**
     * @return Filter
     */
    private function getActiveFilter(): Filter
    {
        return new Filter([
            Filter::KEY_FIELD => 'status',
            Filter::KEY_CONDITION_TYPE => 'eq',
            Filter::KEY_VALUE => 1
        ]);
    }
}
