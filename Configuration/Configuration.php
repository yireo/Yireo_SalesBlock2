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

namespace Yireo\SalesBlock2\Configuration;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Cms\Helper\Page as CmsPageHelper;
use Yireo\SalesBlock2\Exception\CmsPageException;

/**
 * Class Configuration
 *
 * @package Yireo\SalesBlock2\Configuration
 */
class Configuration
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var CmsPageHelper
     */
    private $cmsPageHelper;

    /**
     * Configuration constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param CmsPageHelper $cmsPageHelper
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CmsPageHelper $cmsPageHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->cmsPageHelper = $cmsPageHelper;
    }

    /**
     * Helper-method to check if this module is enabled
     *
     * @return bool
     */
    public function enabled(): bool
    {
        return (bool)$this->scopeConfig->getValue('salesblock/settings/enabled');
    }

    /**
     * Determine the right URL for the custom deny page
     *
     * @return string
     * @throws CmsPageException
     */
    public function getUrl(): string
    {
        $useCustomPage = (bool)$this->scopeConfig->getValue('salesblock/settings/use_custom_page');

        $cmsPageId = $this->scopeConfig->getValue('salesblock/settings/cmspage');
        $cmsPageUrl = $this->cmsPageHelper->getPageUrl($cmsPageId);

        if ($useCustomPage && empty($cmsPageUrl)) {
            throw new CmsPageException('Unknown CMS URL');
        }

        return $cmsPageUrl;
    }
}
