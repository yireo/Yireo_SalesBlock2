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
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Yireo\SalesBlock2\Configuration\Configuration;

/**
 * Class Index
 *
 * @package Yireo\SalesBlock2\Controller\Adminhtml\Rules
 */
class Index extends Action
{
    /**
     * ACL resource
     */
    const ADMIN_RESOURCE = 'Yireo_SalesBlock2::rules';

    /**
     * Message to be shown when the module is disabled by setting
     */
    const ADMIN_MESSAGE_WHEN_DISABLED = 'SalesBlock is currently disabled by setting in the Store Configuration';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Configuration
     */
    private $configuration;


    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Configuration $configuration
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Configuration $configuration
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->configuration = $configuration;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        if (!$this->configuration->enabled()) {
            $this->messageManager->addWarningMessage(self::ADMIN_MESSAGE_WHEN_DISABLED);
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('SalesBlock Rules'));

        return $resultPage;
    }
}
