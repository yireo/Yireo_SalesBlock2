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
 * Class SaveButton
 */
class SaveButton implements ButtonProviderInterface
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
        return [
            'label' => __('Save'),
            'on_click' => sprintf("location.href = '%s';", $this->getButtonUrl()),
            'class' => 'save',
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
        return $this->context->getUrlBuilder()->getUrl('*/*/save');
    }
}
