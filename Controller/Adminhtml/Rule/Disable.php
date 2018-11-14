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
 * Class Disable
 *
 * @package Yireo\SalesBlock2\Controller\Adminhtml\Rules
 */
class Disable extends Massaction
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

        $this->messageManager->addSuccessMessage(__('%1 rules disabled', count($ruleIds)));

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
        $rule->setStatus(0);
        $this->ruleRepository->save($rule);

        return true;
    }
}
