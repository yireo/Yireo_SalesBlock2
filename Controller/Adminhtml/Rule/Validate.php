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
use Magento\Framework\Controller\ResultInterface;

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
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $label = $this->_request->getParam('label');
        throw \Exception('error');
    }
}