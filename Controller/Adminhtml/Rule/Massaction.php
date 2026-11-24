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

namespace Yireo\SalesBlock2\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Yireo\SalesBlock2\Model\ResourceModel\Rule\CollectionFactory;

/**
 * Class Massaction
 *
 * @package Yireo\SalesBlock2\Controller\Adminhtml\Rules
 */
abstract class Massaction extends Action implements HttpPostActionInterface
{
    /**
     * ACL resource
     */
    const ADMIN_RESOURCE = 'Yireo_SalesBlock2::rules';

    /**
     * @var RuleRepositoryInterface
     */
    protected $ruleRepository;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param Context $context
     * @param RuleRepositoryInterface $ruleRepository
     * @param RedirectFactory $resultRedirectFactory
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        RuleRepositoryInterface $ruleRepository,
        RedirectFactory $resultRedirectFactory,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @param callable $callback
     * @param callable $successMessage
     * @return ResultInterface
     */
    protected function processRules(callable $callback, callable $successMessage): ResultInterface
    {
        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('*/*/index');

        try {
            $ruleIds = $this->getRuleIds();
            foreach ($ruleIds as $ruleId) {
                /** @var RuleInterface $rule */
                $rule = $this->ruleRepository->get($ruleId);
                $callback($rule);
            }
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            return $redirect;
        }

        $this->messageManager->addSuccessMessage((string)$successMessage(count($ruleIds)));
        return $redirect;
    }

    /**
     * @return int[]
     * @throws LocalizedException
     */
    protected function getRuleIds(): array
    {
        $singleId = (int)$this->_request->getParam('id');
        if ($singleId > 0) {
            return [$singleId];
        }

        $collection = $this->filter->getCollection($this->collectionFactory->create());
        return array_map('intval', $collection->getAllIds());
    }
}
