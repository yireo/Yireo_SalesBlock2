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
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Yireo\SalesBlock2\Model\Rule\ConditionsValidator;

/**
 * Class Save
 *
 * @package Yireo\SalesBlock2\Controller\Adminhtml\Rules
 */
class Save extends Action implements HttpPostActionInterface
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
     * @var ConditionsValidator
     */
    private $conditionsValidator;

    /**
     * Save constructor.
     *
     * @param Action\Context $context
     * @param RedirectFactory $redirectFactory
     * @param RuleRepositoryInterface $ruleRepository
     * @param ConditionsValidator $conditionsValidator
     */
    public function __construct(
        Action\Context $context,
        RedirectFactory $redirectFactory,
        RuleRepositoryInterface $ruleRepository,
        ConditionsValidator $conditionsValidator
    ) {
        parent::__construct($context);
        $this->ruleRepository = $ruleRepository;
        $this->redirectFactory = $redirectFactory;
        $this->conditionsValidator = $conditionsValidator;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $ruleId = (int)$this->_request->getParam('rule_id');
        $rule = $this->getRuleFromRepository($ruleId);
        $redirect = $this->resultRedirectFactory->create();

        try {
            $this->save($rule);
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $redirect->setPath('*/*/form', $ruleId > 0 ? ['id' => $ruleId] : []);
            return $redirect;
        }

        $this->messageManager->addSuccessMessage(__('Rule has been saved'));
        $redirect->setPath('*/*/index');

        return $redirect;
    }

    /**
     * @param RuleInterface $rule
     *
     * @return bool
     * @throws LocalizedException
     */
    private function save(RuleInterface $rule): bool
    {
        $label = (string) $this->_request->getParam('label');
        $rule->setLabel($label);

        $conditions = $this->conditionsValidator->validate($this->_request->getParam('conditions'));
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
