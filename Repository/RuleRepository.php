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

namespace Yireo\SalesBlock2\Repository;

use Exception;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Api\Search\SearchResult;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Api\SearchResultsFactory;

use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Yireo\SalesBlock2\Model\ResourceModel\Metadata;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Model\ResourceModel\Rule\Collection as RuleCollection;
use Yireo\SalesBlock2\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;

/**
 * Class RuleRepository
 */
class RuleRepository implements RuleRepositoryInterface
{
    /**
     * RuleInterface[]
     *
     * @var array
     */
    protected $registry = [];

    /**
     * @var Metadata
     */
    protected $metadata;

    /**
     * @var RuleCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var SearchResultsFactory
     */
    private $searchResultsFactory;

    /**
     * Repository constructor
     *
     * @param Metadata $metadata
     * @param RuleCollectionFactory $collectionFactory
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsFactory $searchResultsFactory
     */
    public function __construct(
        Metadata $metadata,
        RuleCollectionFactory $collectionFactory,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsFactory $searchResultsFactory
    ) {
        $this->metadata = $metadata;
        $this->collectionFactory = $collectionFactory;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @return SearchCriteriaBuilder
     */
    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        return $this->searchCriteriaBuilderFactory->create();
    }

    /**
     * @param SearchCriteria $searchCriteria
     * @return RuleInterface[]
     */
    public function getItems(SearchCriteria $searchCriteria): array
    {
        $searchResult = $this->getList($searchCriteria);
        return $searchResult->getItems();
    }

    /**
     * @return RuleInterface[]
     */
    public function getAll(): array
    {
        return $this->getItems($this->getSearchCriteriaBuilder()->create());
    }


    /**
     * @return RuleInterface
     */
    public function getEmpty(): RuleInterface
    {
        return $this->metadata->getNewInstance();
    }

    /**
     * Load entity
     *
     * @param int $id
     * @return mixed
     * @throws NoSuchEntityException
     * @throws InputException
     */
    public function getById(int $id): RuleInterface
    {
        if (!$id) {
            throw new InputException(__('ID required'));
        }
        if (!isset($this->registry[$id])) {
            /** @var RuleInterface $entity */
            $entity = $this->metadata->getNewInstance()->load($id);
            if (!$entity->getId()) {
                throw new NoSuchEntityException(__('Requested entity doesn\'t exist'));
            }
            $this->registry[$id] = $entity;
        }
        return $this->registry[$id];
    }

    /**
     * @param int $id
     * @return RuleInterface
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function get(int $id): RuleInterface
    {
        return $this->getById($id);
    }

    /**
     * @return ExtensibleDataInterface
     */
    public function create(): RuleInterface
    {
        return $this->metadata->getNewInstance();
    }

    /**
     * Find entities by criteria
     *
     * @param SearchCriteria $searchCriteria
     * @return SearchResult
     */
    public function getList(SearchCriteria $searchCriteria): SearchResults
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var SearchResult $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Register entity to delete
     *
     * @param RuleInterface $entity
     * @return bool
     * @throws Exception
     */
    public function delete(RuleInterface $entity): bool
    {
        $this->metadata->getMapper()->delete($entity);
        unset($this->registry[$entity->getId()]);
        return true;
    }

    /**
     * Delete entity by Id
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @throws InputException
     */
    public function deleteById(int $id): bool
    {
        $entity = $this->get($id);
        return $this->delete($entity);
    }

    /**
     * Perform persist operations for one entity
     *
     * @param RuleInterface $entity
     * @return RuleInterface
     * @throws AlreadyExistsException
     */
    public function save(RuleInterface $entity)
    {
        $this->metadata->getMapper()->save($entity);
        $this->registry[$entity->getId()] = $entity;
        return $this->registry[$entity->getId()];
    }
}
