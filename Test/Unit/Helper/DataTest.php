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
namespace Yireo\SalesBlock2\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use \Yireo\SalesBlock2\Helper\Data as Target;

/**
 * Class DataTest
 *
 * @package Yireo\SalesBlock2\Test\Unit\Helper
 */
class DataTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var array
	 */
	protected $scopeConfigValues = [];

	/**
	 * @var string
	 */
	protected $cmsPageUrl = '';

	/**
	 * Test whether the enabled flag works
	 */
	public function testEnabled()
	{
		$this->setScopeConfigValue('salesblock/settings/enabled', 1);
		$target = $this->getTargetObject();
		$this->assertSame($target->enabled(), true);

		$this->setScopeConfigValue('salesblock/settings/enabled', 0);
		$target = $this->getTargetObject();
		$this->assertSame($target->enabled(), false);
	}

	/**
	 * Test whether the URL returns some value
	 */
	public function testGetUrl()
	{
		$this->setScopeConfigValue('salesblock/settings/use_custom_page', 1);
		$this->setScopeConfigValue('salesblock/settings/cmspage', 'dummy');
		$this->cmsPageUrl = 'dummy';

		$target = $this->getTargetObject();
		$this->assertNotEmpty($target->getUrl());
	}

	/**
	 * Test whether the URL returns an exception if nothing is filled in
	 *
	 * @expectedException \Exception
	 */
	public function testGetUrlException()
	{
		$this->setScopeConfigValue('salesblock/settings/use_custom_page', 1);
		$this->setScopeConfigValue('salesblock/settings/cmspage', '');
		$this->cmsPageUrl = '';

		$target = $this->getTargetObject();
		$target->getUrl();
	}

	/**
	 * Test whether this is an AJAX request
	 */
	public function testIsAjax()
	{
		$target = $this->getTargetObject();
		$this->assertFalse($target->isAjax());
	}

	/**
	 * @return Target
	 */
	protected function getTargetObject()
	{
		$context = $this->getContextMock();
		$cmsPageHelper = $this->getCmsPageHelperMock();

		$target = new Target($cmsPageHelper, $context);

		return $target;
	}

	/**
	 * @return \Magento\Cms\Helper\Page
	 */
	protected function getCmsPageHelperMock()
	{
		$cmsPageHelper = $this->createMock(
			'Magento\Cms\Helper\Page',
			[],
			[],
			'',
			false,
			false
		);

		$cmsPageHelper->expects($this->any())
			->method('getPageUrl')
			->will($this->returnValue($this->cmsPageUrl)
			);

		return $cmsPageHelper;
	}

	/**
	 * @return Context
	 */
	protected function getContextMock()
	{
		$context = $this->createMock(
			'Magento\Framework\App\Helper\Context',
			[],
			[],
			'',
			false,
			false
		);

		$scopeConfig = $this->getScopeConfigMock();
		$context->expects($this->any())
			->method('getScopeConfig')
			->will($this->returnValue($scopeConfig)
			);

		$request = $this->getRequestMock();
		$context->expects($this->any())
			->method('getRequest')
			->will($this->returnValue($request)
			);

		return $context;
	}

	/**
	 * @return ScopeConfigInterface
	 */
	protected function getScopeConfigMock()
	{
		$scopeConfig = $this->createMock('Magento\Framework\App\Config\ScopeConfigInterface');

		$scopeConfig->expects($this->any())
			->method('getValue')
			->will($this->returnValueMap($this->scopeConfigValues));

		return $scopeConfig;
	}

	/**
	 * @return Http
	 */
	protected function getRequestMock()
	{
		$request = $this->createMock('Magento\Framework\App\Request\Http',
			[],
			[],
			'',
			false,
			false
		);

		$request->expects($this->any())
			->method('isAjax')
			->will($this->returnValue(false)
			);

		return $request;
	}

	/**
	 * @return array
	 */
	protected function setScopeConfigValue($name, $value)
	{
		$this->scopeConfigValues[$name] = [$name, 'default', null, $value];
	}
}