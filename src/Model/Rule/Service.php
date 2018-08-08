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
namespace Yireo\SalesBlock2\Model\Rule;

use Magento\Framework\Api\Filter;

/**
 * Class Service
 *
 * @package Yireo\SalesBlock2\Model\Rule
 */
class Service
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
	 * Service constructor.
	 *
	 * @param \Yireo\SalesBlock2\Api\RuleRepositoryInterface      $ruleRepository
	 * @param \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder
	 *
	 */
	public function __construct(
		\Yireo\SalesBlock2\Api\RuleRepositoryInterface $ruleRepository,
		\Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder
	)
	{
		$this->ruleRepository = $ruleRepository;
		$this->searchCriteriaBuilder = $searchCriteriaBuilder;
	}

	/**
	 * Helper-method to fetch all rules
	 *
	 * @return \Yireo\SalesBlock2\Api\Data\RuleInterface[]
	 */
	public function getRules()
	{
		$searchCriteriaBuilder = $this->searchCriteriaBuilder;
		$searchCriteriaBuilder->addFilter(new Filter([
			Filter::KEY_FIELD => 'status',
			Filter::KEY_CONDITION_TYPE => 'eq',
			Filter::KEY_VALUE => 1
		]));

		$searchCriteria = $searchCriteriaBuilder->create();
		$rules = $this->ruleRepository->getList($searchCriteria);

		return $rules;
	}
}