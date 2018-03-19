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

    public function __construct(
        Action\Context $context,
        RedirectFactory $redirectFactory,
        RuleRepositoryInterface $ruleRepository
    )
    {
        parent::__construct($context);
        $this->ruleRepository = $ruleRepository;
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $data = [];
        $data['label'] = $this->_request->getParam('label');

        $ruleId = (int) $this->_request->getParam('id');
        $this->save($ruleId, $data);

        /** @var Page $resultPage */
        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('*/*/index');

        return $redirect;
    }

    /**
     * @param int $ruleId
     * @param $data
     * @return bool
     */
    private function save(int $ruleId, $data): bool
    {
        $rule = $this->getRuleFromRepository($ruleId);
        $rule->setData($data);
        $this->ruleRepository->save($rule);

        return true;
    }

    /**
     * @param int $ruleId
     * @return RuleInterface
     */
    private function getRuleFromRepository(int $ruleId) : RuleInterface
    {
        if ($ruleId > 0) {
            return $this->ruleRepository->get($ruleId);
        }

        return $this->ruleRepository->create();
    }
}