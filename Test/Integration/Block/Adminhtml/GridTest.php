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
     * @magentoAppArea adminhtml
     */
    public function testRouteIsConfigured()
    {
        $routeConfig = Bootstrap::getObjectManager()->create(RouteConfigInterface::class);
        $this->assertContains('Yireo_SalesBlock2', $routeConfig->getModulesByFrontName('salesblock'));
    }

    /**
     * @magentoAppArea adminhtml
     */
    public function testActionControllerIsFound()
    {
        $request = Bootstrap::getObjectManager()->create(Request::class);
        $request->setModuleName('salesblock');
        $request->setControllerName('rule');
        $request->setActionName('index');
        /** @var Router $baseRouter */
        $baseRouter = Bootstrap::getObjectManager()->create(Router::class);
        $expectedAction = Index::class;
        $this->assertInstanceOf($expectedAction, $baseRouter->match($request));
    }

    /**
     *
     */
    public function testReturnsResultInstance()
    {
        $context = Bootstrap::getObjectManager()->create(ActionContext::class);
        $resultPageFactory = new PageFactory(Bootstrap::getObjectManager());
        $controller = new Index($context, $resultPageFactory);
        $result = $controller->execute();
        $this->assertInstanceOf(ResultPage::class, $result);
    }

    /**
     * Test whether the page contains valid body content
     *
     * @magentoAppArea adminhtml
     */
    public function testValidBodyContent()
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();
        $request->setMethod(Request::METHOD_GET);
        $this->dispatch($this->uri);

        /** @var \Magento\Framework\App\Response\Http $response */
        $response = $this->getResponse();
        $body = $response->getBody();
        $this->assertNotEmpty($body, 'HTTP headers: '.var_export($response->getHeaders(), true));
        $this->assertRegExp('#<body [^>]+>#s', $body);
        $this->assertContains('Dashboard', $body);
    }

    /**
     * Override of core method, because actually gives 302
     */
    public function testAclNoAccess()
    {
        if ($this->resource === null) {
            $this->markTestIncomplete('Acl test is not complete');
        }
        $this->_objectManager->get(\Magento\Framework\Acl\Builder::class)
            ->getAcl()
            ->deny(null, $this->resource);
        $this->dispatch($this->uri);

        /** @var \Magento\Framework\App\Response\Http $response */
        $response = $this->getResponse();
        $this->assertSame(302, $response->getHttpResponseCode());
    }
}
