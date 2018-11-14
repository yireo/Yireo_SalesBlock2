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

namespace Yireo\SalesBlock2\Model\ResourceModel\Rule;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Api\Search\AggregationInterface;
use Yireo\SalesBlock2\Model\ResourceModel\Rule as RuleResourceModel;
use Yireo\SalesBlock2\Model\Rule;

/**
 * Class Collection
 *
 * @package Yireo\SalesBlock2\Model\ResourceModel\Rule
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'rule_id';

    /**
     * @var SearchCriteriaInterface
     */
    protected $searchCriteria;

    /**
     * @var AggregationInterface[]
     */
    protected $aggregations;

    /**
     *
     */
    protected function _construct()
    {
        $this->_init(Rule::class, RuleResourceModel::class);
    }

    /**
     * Get search criteria.
     *
     * @return SearchCriteriaInterface|null
     */
    public function getSearchCriteria()
    {
        return $this->searchCriteria;
    }

    /**
     * Set search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return $this
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria = null)
    {
        $this->searchCriteria = $searchCriteria;
        return $this;
    }
}
