<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Api;

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
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Yireo\SalesBlock2\Api\Data\RuleInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteria $searchCriteria);

    /**
     * Loads a specified invoice item.
     *
     * @param int $id
     * @return \Yireo\SalesBlock2\Api\Data\RuleInterface
     */
    public function get($id);

    /**
     * Create a new rule
     *
     * @return \Yireo\SalesBlock2\Api\Data\RuleInterface
     */
    public function create();

    /**
     * Deletes a rule
     *
     * @param \Yireo\SalesBlock2\Api\Data\RuleInterface $entity
     * @return bool
     */
    public function delete(\Yireo\SalesBlock2\Api\Data\RuleInterface $entity);

    /**
     * Saves a rule
     *
     * @param \Yireo\SalesBlock2\Api\Data\RuleInterface $entity
     * @return \Yireo\SalesBlock2\Api\Data\RuleInterface
     */
    public function save(\Yireo\SalesBlock2\Api\Data\RuleInterface $entity);
}