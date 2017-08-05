<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types = 1);
namespace Yireo\SalesBlock2\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\RequestInterface as Request;
use Magento\Cms\Helper\Page as CmsPageHelper;

/**
 * Class Data
 *
 * @package Yireo\SalesBlock2\Helper
 */
class Data extends AbstractHelper
{
	/**
	 * @var \Magento\Cms\Helper\Page
	 */
	private $cmsPageHelper;

	/**
	 * Data constructor.
	 *
	 * @param CmsPageHelper $cmsPageHelper
	 * @param Context       $context
	 */
	public function __construct(
		CmsPageHelper $cmsPageHelper,
		Context $context
	)
	{
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
		return (bool)$this->scopeConfig->getValue('salesblock/settings/enabled');
	}

	/**
	 * Determine the right URL for the custom deny page
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function getUrl()
	{
		$useCustomPage = (bool) $this->scopeConfig->getValue('salesblock/settings/use_custom_page');

		$cmsPageId = $this->scopeConfig->getValue('salesblock/settings/cmspage');
		$cmsPageUrl = $this->cmsPageHelper->getPageUrl($cmsPageId);

		if ($useCustomPage && empty($cmsPageUrl))
		{
			throw new \Exception('Unknown CMS URL');
		}

		return $cmsPageUrl;
	}


	/**
	 * Determine whether the current request is AJAX
	 *
	 * @return bool
	 */
	public function isAjax()
	{
		/** @var \Magento\Framework\App\RequestInterface $request */
		$request = $this->_request;
		if ($request->isAjax())
		{
			return true;
		}

		return false;
	}
}