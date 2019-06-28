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
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;

/**
 * Class Save
 *
 * @package Yireo\SalesBlock2\Controller\Adminhtml\Rules
 */
class Save extends Action
{
    /**
     * ACL resource
     */
    const ADMIN_RESOURCE = 'Yireo_SalesBlock2::rules';

    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * Save constructor.
     *
     * @param Action\Context $context
     * @param RedirectFactory $redirectFactory
     * @param RuleRepositoryInterface $ruleRepository
     */
    public function __construct(
        Action\Context $context,
        RedirectFactory $redirectFactory,
        RuleRepositoryInterface $ruleRepository
    ) {
        parent::__construct($context);
        $this->ruleRepository = $ruleRepository;
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $ruleId = (int)$this->_request->getParam('rule_id');
        $rule = $this->getRuleFromRepository($ruleId);

        $this->save($rule);
        $this->messageManager->addSuccessMessage(__('Rule has been saved'));

        /** @var Page $resultPage */
        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('*/*/index');

        return $redirect;
    }

    /**
     * @param RuleInterface $rule
     *
     * @return bool
     */
    private function save(RuleInterface $rule): bool
    {
        $label = (string) $this->_request->getParam('label');
        $rule->setLabel($label);

        $conditions = $this->_request->getParam('conditions');
        $rule->setConditions($conditions);

        $status = (int) $this->_request->getParam('status');
        $rule->setStatus($status);

        $frontendLabel = (string) $this->_request->getParam('frontend_label');
        $rule->setFrontendLabel($frontendLabel);

        $frontendText = (string) $this->_request->getParam('frontend_text');
        $rule->setFrontendText($frontendText);

        $this->ruleRepository->save($rule);

        return true;
    }

    /**
     * @param int $ruleId
     *
     * @return RuleInterface
     */
    private function getRuleFromRepository(int $ruleId): RuleInterface
    {
        if ($ruleId > 0) {
            return $this->ruleRepository->get($ruleId);
        }

        return $this->ruleRepository->create();
    }
}
