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

namespace Yireo\SalesBlock2\Test\Unit\Helper;

use Magento\Cms\Helper\Page as CmsPageHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Configuration\Configuration;
use Yireo\SalesBlock2\Helper\Data as Target;

/**
 * Class DataTest
 *
 * @package Yireo\SalesBlock2\Test\Unit\Helper
 */
class DataTest extends TestCase
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
        $configuration = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $target = new Target($context, $cmsPageHelper, $configuration);

        return $target;
    }

    /**
     * @return CmsPageHelper
     */
    protected function getCmsPageHelperMock()
    {
        $cmsPageHelper = $this->createMock(CmsPageHelper::class);

        $cmsPageHelper->expects($this->any())
            ->method('getPageUrl')
            ->willReturn($this->cmsPageUrl);

        return $cmsPageHelper;
    }

    /**
     * @return Context
     */
    protected function getContextMock()
    {
        $context = $this->createMock(Context::class);

        $scopeConfig = $this->getScopeConfigMock();
        $context->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($scopeConfig);

        $request = $this->getRequestMock();
        $context->expects($this->any())
            ->method('getRequest')
            ->willReturn($request);

        return $context;
    }

    /**
     * @return ScopeConfigInterface
     */
    protected function getScopeConfigMock()
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);

        $scopeConfig->expects($this->any())
            ->method('getValue')
            ->willReturnMap($this->scopeConfigValues);

        return $scopeConfig;
    }

    /**
     * @return Http
     */
    protected function getRequestMock()
    {
        $request = $this->createMock(Http::class);

        $request->expects($this->any())
            ->method('isAjax')
            ->willReturn(false);

        return $request;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    protected function setScopeConfigValue(string $name, $value): void
    {
        $this->scopeConfigValues[$name] = [$name, 'default', null, $value];
    }
}
