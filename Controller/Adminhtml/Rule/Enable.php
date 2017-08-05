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

use \Magento\Framework\Controller\Result\Redirect;
use \Yireo\SalesBlock2\Api\Data\RuleInterface;

/**
 * Class Enable
 *
 * @package Yireo\SalesBlock2\Controller\Adminhtml\Rules
 */
class Enable extends Massaction
{
    /**
     * @return Redirect
     */
    public function execute()
    {
        $ruleIds = $this->getRuleIds();
        foreach ($ruleIds as $ruleId) {
            $this->enableByRuleId($ruleId);
        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('*/*/index');

        return $redirect;
    }

    /**
     * @param $ruleId
     * @return bool
     */
    private function enableByRuleId($ruleId)
    {
        /** @var RuleInterface $rule */
        $rule = $this->ruleRepository->get($ruleId);
        $rule->setStatus(1);
        $this->ruleRepository->save($rule);

        return true;
    }
}