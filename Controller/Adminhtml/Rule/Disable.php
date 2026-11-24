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

use Magento\Framework\Controller\ResultInterface;
use Yireo\SalesBlock2\Api\Data\RuleInterface;

class Disable extends Massaction
{
    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        return $this->processRules(function (RuleInterface $rule) {
            $rule->setStatus(0);
            $this->ruleRepository->save($rule);
        }, fn (int $count) => __('%1 rules disabled', $count));
    }
}
