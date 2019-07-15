<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Controller\Adminhtml;

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
    protected function setUp()
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
            if (!$header instanceof \Zend\Http\Header\Location) {
                continue;
            }

            $msg = 'Redirect to: ' . $header->getUri();
        }

        $this->assertSame(200, $response->getHttpResponseCode(), $msg);

        $body = $response->getBody();
        $this->assertNotEmpty($body);
        $this->assertRegExp('#<body [^>]+>#s', $body);
        $this->assertContains('SalesBlock Rules', $body);
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
        $this->assertNotContains(Controller::ADMIN_MESSAGE_WHEN_DISABLED, $body);
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
        $this->assertContains(Controller::ADMIN_MESSAGE_WHEN_DISABLED, $body);
    }
}
