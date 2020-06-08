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

namespace Yireo\SalesBlock2\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Cms\Helper\Page as CmsPageHelper;
use Yireo\SalesBlock2\Configuration\Configuration;
use Yireo\SalesBlock2\Exception\CmsPageException;

/**
 * Class Data
 *
 * @package Yireo\SalesBlock2\Helper
 * @todo: Remove this helper
 */
class Data extends AbstractHelper
{
    /**
     * @var \Magento\Cms\Helper\Page
     */
    private $cmsPageHelper;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param CmsPageHelper $cmsPageHelper
     * @param Configuration $configuration
     */
    public function __construct(
        Context $context,
        CmsPageHelper $cmsPageHelper,
        Configuration $configuration
    ) {
        parent::__construct($context);
        $this->cmsPageHelper = $cmsPageHelper;
        $this->configuration = $configuration;
    }

    /**
     * Determine the right URL for the custom deny page
     *
     * @return string
     * @throws CmsPageException
     * @deprecated Use Configuration::getUrl() instead
     */
    public function getUrl(): string
    {
        return $this->configuration->getUrl();
    }

    /**
     * Determine whether the current request is AJAX
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        /** @var \Magento\Framework\App\RequestInterface $request */
        $request = $this->_request;
        if ($request->isAjax()) {
            return true;
        }

        return false;
    }

    /**
     * Convert a string into an array
     *
     * @param string $string
     *
     * @return array
     */
    public function stringToArray(string $string): array
    {
        $data = preg_split("/(\n|,|;|\|)/", $string);
        $newData = [];

        foreach ($data as $value) {
            $value = trim($value);
            if (!empty($value)) {
                $newData[] = $value;
            }
        }

        return $newData;
    }
}
