<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Block\Adminhtml;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\AbstractBackendController;
use Magento\TestFramework\Request;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Router;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\View\Result\Page\Interceptor as ResultPage;
use Magento\Framework\App\Route\ConfigInterface as RouteConfigInterface;
use Yireo\SalesBlock2\Controller\Adminhtml\Rule\Index;

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
     *
     * @magentoAppArea adminhtml
     */
    public function testValidBodyContent()
    {
        $this->assertTrue($this->_auth->isLoggedIn());

        /** @var \Magento\Framework\App\Request\Http $request */
        //$request = $this->getRequest();
        //$request->setMethod(Request::METHOD_GET);
        $this->dispatch($this->uri);

        /** @var \Magento\Framework\App\Response\Http $response */
        $response = $this->getResponse();

        if ($response->getHttpResponseCode() !== 200) {
            $msg = 'Unexpected HTTP Status code: ' . $response->getHttpResponseCode();
            $this->fail('Unexpected HTTP Status code: ' . $msg);
            return;
        }

        $body = $response->getBody();
        $this->assertNotEmpty($body);
        $this->assertRegExp('#<body [^>]+>#s', $body);
        $this->assertContains('Dashboard', $body);
    }
}
