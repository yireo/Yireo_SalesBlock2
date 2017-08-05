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

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

/**
 * Class View
 *
 * @package Yireo\SalesBlock2\Controller\Adminhtml\Rules
 */
class View extends Action
{
    /**
     * ACL resource
     */
    const ADMIN_RESOURCE = 'Yireo_SalesBlock2::rules';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        echo '@todo: Implement view';exit;

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Yireo_SalesBlock2::rules');
        $resultPage->addBreadcrumb(__('SalesBlock Rules'), __('SalesBlock Rules'));
        $resultPage->getConfig()->getTitle()->prepend(__('SalesBlock Rules'));

        return $resultPage;
    }
}