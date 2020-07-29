<?php

declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Adminhtml;

use Laminas\Http\Header\Location;
use Yireo\SalesBlock2\Controller\Adminhtml\Rule\Index as Controller;
use Magento\TestFramework\TestCase\AbstractBackendController;

/**
 *
 */
class GridTest extends AbstractBackendController
{
    /**
     * Setup method
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->resource = 'Yireo_SalesBlock2::rules';
        $this->uri = 'backend/salesblock/rule/index';
    }

    /**
     * Test whether the page contains valid body content
     */
    public function testValidBodyContent()
    {
        $this->assertTrue($this->_session->isLoggedIn());
        $this->assertTrue($this->_session->isAllowed($this->resource));

        $this->dispatch($this->uri);
        $this->assertTrue($this->_session->isLoggedIn(), 'Session is no longer valid');

        /** @var \Magento\Framework\App\Response\Http $response */
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $msg = '';
        foreach ($headers as $header) {
            if (!$header instanceof Location) {
                continue;
            }

            $msg = 'Redirect to: ' . $header->getUri();
        }

        $this->assertSame(200, $response->getHttpResponseCode(), $msg);

        $body = $response->getBody();
        $this->assertNotEmpty($body);
        $this->assertTrue((bool)preg_match('#<body [^>]+>#s', $body));
        $this->assertTrue((bool)strpos($body, 'SalesBlock Rules'));
    }

    /**
     * Test whether the body does not contain a proper message when the module is enabled
     *
     * @magentoConfigFixture default/salesblock/settings/enabled 1
     */
    public function testValidMessageWhenModuleIsEnabled()
    {
        $this->dispatch($this->uri);
        $body = $this->getResponse()->getBody();
        $this->assertFalse((bool)strpos($body, Controller::ADMIN_MESSAGE_WHEN_DISABLED));
    }

    /**
     * Test whether the body contains a proper message when the module is disabled
     *
     * @magentoConfigFixture default/salesblock/settings/enabled 0
     */
    public function testValidMessageWhenModuleIsDisabled()
    {
        $this->dispatch($this->uri);
        $body = $this->getResponse()->getBody();
        $this->assertTrue((bool)strpos($body, Controller::ADMIN_MESSAGE_WHEN_DISABLED));
    }
}
