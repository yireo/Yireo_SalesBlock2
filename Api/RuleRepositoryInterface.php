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

use Magento\Framework\Api\SearchCriteria;
use Yireo\SalesBlock2\Api\Data\RuleInterface;

/**
 * Class RuleRepositoryInterface
 *
 * @package Yireo\SalesBlock2\Api
 */
interface RuleRepositoryInterface
{
    /**
     * Lists the invoice items that match specified search criteria.
     *
     * @param SearchCriteria $searchCriteria
     *
     * @return RuleInterface[]
     */
    public function getList(SearchCriteria $searchCriteria);

    /**
     * Loads a specified invoice item.
     *
     * @param int $id
     *
     * @return RuleInterface
     */
    public function get($id);

    /**
     * Create a new rule
     *
     * @return RuleInterface
     */
    public function create();

    /**
     * Deletes a rule
     *
     * @param RuleInterface $entity
     *
     * @return bool
     */
    public function delete(RuleInterface $entity);

    /**
     * Saves a rule
     *
     * @param RuleInterface $entity
     *
     * @return RuleInterface
     */
    public function save(RuleInterface $entity);
}
