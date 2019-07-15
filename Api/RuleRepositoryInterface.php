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

namespace Yireo\SalesBlock2\Api;

use Magento\Framework\Api\Search\SearchResult;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResults;
use Yireo\SalesBlock2\Api\Data\RuleInterface;

/**
 * Class RuleRepositoryInterface
 *
 * @package Yireo\SalesBlock2\Api
 */
interface RuleRepositoryInterface
{
    /**
     * @return SearchCriteriaBuilder
     */
    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder;

    /**
     * @param SearchCriteria $searchCriteria
     * @return RuleInterface[]
     */
    public function getItems(SearchCriteria $searchCriteria): array;

    /**
     * @param SearchCriteria $searchCriteria
     * @return SearchResult
     */
    public function getList(SearchCriteria $searchCriteria): SearchResults;

    /**
     * @return RuleInterface[]
     */
    public function getAll(): array;

    /**
     * @param int $id
     * @return RuleInterface
     * @deprecated Use getById() instead because it looks cleaner
     */
    public function get(int $id): RuleInterface;

    /**
     * @param int $id
     * @return RuleInterface
     */
    public function getById(int $id): RuleInterface;

    /**
     * @return RuleInterface
     */
    public function getEmpty(): RuleInterface;

    /**
     * Create a new rule
     *
     * @return RuleInterface
     */
    public function create(): RuleInterface;

    /**
     * Deletes a rule
     *
     * @param RuleInterface $entity
     * @return bool
     */
    public function delete(RuleInterface $entity): bool;

    /**
     * Delete entity by Id
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;

    /**
     * Saves a rule
     *
     * @param RuleInterface $entity
     *
     * @return RuleInterface
     */
    public function save(RuleInterface $entity);
}
