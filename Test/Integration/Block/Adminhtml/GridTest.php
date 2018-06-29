<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Block\Adminhtml;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\AbstractBackendController;
use Magento\TestFramework\Request;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context as ActionContext;
use Yireo\SalesBlock2\Controller\Adminhtml\Rule\Index;
use Magento\Framework\View\Result\Page\Interceptor as ResultPage;

/**
 *
 */
class GridTest extends AbstractBackendController
{
    /**
     * Setup method
     */
    public function setUp()
    {
        $this->resource = 'Yireo_SalesBlock2::rules';
        $this->uri = 'backend/salesblock/rule/index';
        parent::setUp();
    }

    /**
     *
     */
    public function testReturnsResultInstance()
    {
        $context = Bootstrap::getObjectManager()->create(ActionContext::class);
        $resultPageFactory = new PageFactory(Bootstrap::getObjectManager());
        $this->controller = new Index($context, $resultPageFactory);
        $result = $this->controller->execute();
        $this->assertInstanceOf(ResultPage::class, $result);
    }

    /**
     *
     */
    public function testCanHandleGetRequests()
    {
        $this->getRequest()->setMethod(Request::METHOD_GET);
        $this->dispatch($this->uri);
        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());
    }

    /**
     * Test whether the page contains valid body content
     */
    public function testValidBodyContent()
    {
        $this->dispatch($this->uri);
        $body = $this->getResponse()->getBody();
        $this->assertContains('SalesBlock', $body);
    }
}
