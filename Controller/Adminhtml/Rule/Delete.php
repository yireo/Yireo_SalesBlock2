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

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultInterface;
use Yireo\SalesBlock2\Api\Data\RuleInterface;

/**
 * Class Delete
 *
 * @package Yireo\SalesBlock2\Controller\Adminhtml\Rules
 */
class Delete extends Massaction
{
    /**
     * @return ResultInterface
     */
    public function execute() : ResultInterface
    {
        $ruleIds = $this->getRuleIds();
        foreach ($ruleIds as $ruleId) {
            $this->disableByRuleId($ruleId);
        }

        $this->messageManager->addSuccessMessage(__('%1 rules deleted', count($ruleIds)));

        /** @var Page $resultPage */
        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('*/*/index');

        return $redirect;
    }

    /**
     * @param int $ruleId
     *
     * @return bool
     */
    private function disableByRuleId(int $ruleId) : bool
    {
        /** @var RuleInterface $rule */
        $rule = $this->ruleRepository->get($ruleId);
        $this->ruleRepository->delete($rule);

        return true;
    }
}