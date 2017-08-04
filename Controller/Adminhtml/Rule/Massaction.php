<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;

/**
 * Class Massaction
 *
 * @package Yireo\SalesBlock2\Controller\Adminhtml\Rules
 */
abstract class Massaction extends Action
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
     * Index constructor.
     *
     * @param Context $context
     * @param RuleRepositoryInterface $ruleRepository
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        Context $context,
        RuleRepositoryInterface $ruleRepository,
        RedirectFactory $resultRedirectFactory
    )
    {
        $this->ruleRepository = $ruleRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        parent::__construct($context);
    }

    /**
     * @return array
     */
    protected function getRuleIds()
    {
        $params = $this->_request->getParams();

        $ruleIds = [];
        $ruleId = (int) $this->_request->getParam('id');
        if (!empty($ruleId)) {
            $ruleIds[] = $ruleId;
        }

        return $ruleIds;
    }
}