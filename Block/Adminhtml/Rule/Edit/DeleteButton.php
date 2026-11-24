<?php declare(strict_types=1);

/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Block\Adminhtml\Rule\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 */
class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        $this->context = $context;
    }

    /**
     * @return string[]
     */
    public function getButtonData()
    {
        $escaper = $this->context->getEscaper();
        $confirmMessage = $escaper->escapeJs($escaper->escapeHtml(__('Are you sure you want to delete this rule?')));

        return [
            'label' => __('Delete'),
            'on_click' => sprintf(
                "deleteConfirm('%s', '%s', {\"data\": {}})",
                $confirmMessage,
                $escaper->escapeJs($this->getButtonUrl())
            ),
            'class' => 'delete',
            'sort_order' => 1
        ];
    }

    /**
     * Get URL for this button
     *
     * @return string
     */
    public function getButtonUrl(): string
    {
        $ruleId = (int)$this->context->getRequest()->getParam('id');
        return $this->context->getUrlBuilder()->getUrl('*/*/delete', ['id' => $ruleId]);
    }
}
