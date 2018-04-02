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
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;

/**
 * Class Validate
 *
 * @package Yireo\SalesBlock2\Controller\Adminhtml\Rules
 */
class Validate extends Action
{
    /**
     * ACL resource
     */
    const ADMIN_RESOURCE = 'Yireo_SalesBlock2::rules';

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    public function __construct(
        Action\Context $context,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $response = new DataObject();
        $response->setError(null);

        $this->validateRule($response);

        $resultJson = $this->resultJsonFactory->create();

        if ($response->getError()) {
            $response->setError(true);
            $response->setMessages($response->getMessages());
        }

        $resultJson->setData($response);
        return $resultJson;
    }

    /**
     * @param DataObject $response
     *
     * @return bool
     */
    private function validateRule(DataObject $response) : bool
    {
        $messages = [];

        $label = $this->_request->getParam('label');
        if (empty($label)) {
            $messages[] = __('Label can not be empty');
        }

        $response->setMessages($messages);
        $response->setError(count($messages));

        return (bool) (empty($messages));
    }
}