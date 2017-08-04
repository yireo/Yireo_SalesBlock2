<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Data
 *
 * @package Yireo\SalesBlock2\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @var \Yireo\SalesBlock2\Api\RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var \Magento\Framework\Api\Search\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Cms\Helper\Page
     */
    private $cmsPageHelper;

    public function __construct(
        \Yireo\SalesBlock2\Api\RuleRepositoryInterface $ruleRepository,
        \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Cms\Helper\Page $cmsPageHelper,
        Context $context
    )
    {
        $this->ruleRepository = $ruleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->cmsPageHelper = $cmsPageHelper;

        parent::__construct($context);
    }

    /**
     * Helper-method to check if this module is enabled
     *
     * @return bool
     */
    public function enabled()
    {
        if ((bool)$this->scopeConfig->getValue('advanced/modules_disable_output/Yireo_SalesBlock2')) {
            return false;
        }

        return (bool)$this->scopeConfig->getValue('salesblock/settings/enabled');
    }

    /**
     * Helper-method to fetch all rules
     *
     * @return \Yireo\SalesRules2\Model\ResourceModel\Rule\Collection
     */
    public function getRules()
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilder;
        $searchCriteriaBuilder->addFilter(new \Magento\Framework\Api\Filter([
            Filter::KEY_FIELD => 'status',
            Filter::KEY_CONDITION_TYPE => 'eq',
            Filter::KEY_VALUE => 1
        ]));

        $searchCriteria = $searchCriteriaBuilder->create();
        $rules = $this->ruleRepository->getList($searchCriteria);

        return $rules;
    }

    /**
     * Determine the right URL for the custom deny page
     *
     * @return string
     */
    public function getUrl()
    {
        $custom_page = (int)$this->scopeConfig->getValue('salesblock/settings/custom_page');
        $cmsPageId = $this->scopeConfig->getValue('salesblock/settings/cmspage');
        $cmsPageUrl = $this->cmsPageHelper->getPageUrl($cmsPageId);

        if ($custom_page == 1 || empty($cmsPageUrl)) {
            throw new \Exception('bug');
            //return Mage::getUrl('salesblock');
        } else {
            return $cmsPageUrl;
        }
    }


    /**
     * Determine whether the current request is AJAX
     *
     * @return bool
     */
    public function isAjax()
    {
        $request = $this->_request;

        if ($request->isXmlHttpRequest()) {
            return true;
        }

        if ($request->getParam('ajax') || $request->getParam('isAjax')) {
            return true;
        }

        return false;
    }
}