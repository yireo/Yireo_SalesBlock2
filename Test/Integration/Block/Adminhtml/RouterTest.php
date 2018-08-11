<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Block\Adminhtml;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\Request;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Router;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\View\Result\Page\Interceptor as ResultPage;
use Magento\Framework\App\Route\ConfigInterface as RouteConfigInterface;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Controller\Adminhtml\Rule\Index;

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
}
