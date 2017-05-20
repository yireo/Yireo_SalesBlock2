<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Model\Rule;

use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Yireo\SalesBlock2\Model\ResourceModel\Metadata;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Model\ResourceModel\Rule\Collection as RuleCollection;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Repository
 */
class Repository implements RuleRepositoryInterface
{
    /**
     * \Magento\Sales\Api\Data\InvoiceInterface[]
     *
     * @var array
     */
    protected $registry = [];

    /**
     * @var Metadata
     */
    protected $metadata;

    /**
     * @var RuleCollection
     */
    protected $collection;

    /**
     * Repository constructor
     *
     * @param Metadata $metadata
     * @param RuleCollection $collection
     */
    public function __construct(
        Metadata $metadata,
        RuleCollection $collection
    ) {
        $this->metadata = $metadata;
        $this->collection = $collection;
    }

    /**
     * Load entity
     *
     * @param int $id
     * @return mixed
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function get($id)
    {
        if (!$id) {
            throw new \Magento\Framework\Exception\InputException(__('ID required'));
        }
        if (!isset($this->registry[$id])) {
            /** @var RuleInterface $entity */
            $entity = $this->metadata->getNewInstance()->load($id);
            if (!$entity->getEntityId()) {
                throw new NoSuchEntityException(__('Requested entity doesn\'t exist'));
            }
            $this->registry[$id] = $entity;
        }
        return $this->registry[$id];
    }

    /**
     * @return RuleInterface
     */
    public function create()
    {
        return $this->metadata->getNewInstance();
    }

    /**
     * Find entities by criteria
     *
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return RuleInterface[]
     */
    public function getList(\Magento\Framework\Api\SearchCriteria $searchCriteria)
    {
        /** @var \Yireo\SalesBlock2\Model\ResourceModel\Rule\Collection $collection */
        $collection = $this->collection;

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        $collection->setSearchCriteria($searchCriteria);
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        return $collection;
    }

    /**
     * Register entity to delete
     *
     * @param RuleInterface $entity
     * @return bool
     */
    public function delete(RuleInterface $entity)
    {
        $this->metadata->getMapper()->delete($entity);
        unset($this->registry[$entity->getEntityId()]);
        return true;
    }

    /**
     * Delete entity by Id
     *
     * @param int $id
     * @return bool
     */
    public function deleteById($id)
    {
        $entity = $this->get($id);
        return $this->delete($entity);
    }

    /**
     * Perform persist operations for one entity
     *
     * @param RuleInterface $entity
     * @return RuleInterface
     */
    public function save(RuleInterface $entity)
    {
        $this->metadata->getMapper()->save($entity);
        $this->registry[$entity->getEntityId()] = $entity;
        return $this->registry[$entity->getEntityId()];
    }
}
