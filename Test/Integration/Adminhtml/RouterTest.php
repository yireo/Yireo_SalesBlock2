<?php

declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Adminhtml;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\Request;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Router;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\View\Result\Page\Interceptor as ResultPage;
use Magento\Framework\App\Route\ConfigInterface as RouteConfigInterface;
use Magento\Framework\App\Route\Config as RouteConfig;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Configuration\Configuration;
use Yireo\SalesBlock2\Controller\Adminhtml\Rule\Index as IndexController;

/**
 *
 */
class RouterTest extends TestCase
{
    /**
     * @magentoAppArea adminhtml
     */
    public function testRouteIsConfigured()
    {
        /** @var RouteConfig $routeConfig */
        $routeConfig = Bootstrap::getObjectManager()->create(RouteConfigInterface::class);
        $route = $routeConfig->getRouteFrontName('salesblock');
        $this->assertEquals('salesblock', $route);

        $modules = $routeConfig->getModulesByFrontName('salesblock', 'adminhtml');
        $module = $modules[0];
        $this->assertEquals('Yireo_SalesBlock2', $module);
    }

    /**
     * @magentoAppArea adminhtml
     */
    public function testActionControllerIsFound()
    {
        /** @var Request $request */
        $request = Bootstrap::getObjectManager()->create(Request::class);
        $request->setModuleName('salesblock');
        $request->setControllerName('rule');
        $request->setActionName('index');
        $request->setRequestUri('salesblock/rule/index');
        $request->setPathInfo('salesblock/rule/index');

        /** @var Router $baseRouter */
        $baseRouter = Bootstrap::getObjectManager()->create(Router::class);
        $expectedAction = IndexController::class;
        $this->assertInstanceOf($expectedAction, $baseRouter->match($request));
    }

    /**
     *
     */
    public function testReturnsResultInstance()
    {
        $context = Bootstrap::getObjectManager()->create(ActionContext::class);
        $resultPageFactory = new PageFactory(Bootstrap::getObjectManager());
        $configuration = Bootstrap::getObjectManager()->create(Configuration::class);
        $controller = new IndexController($context, $resultPageFactory, $configuration);
        $result = $controller->execute();
        $this->assertInstanceOf(ResultPage::class, $result);
    }
}
