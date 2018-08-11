<?php
//declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Block\Adminhtml;

use Magento\TestFramework\Request;
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
        $this->assertNotEmpty($this->_auth);
        $this->assertTrue($this->_session->isLoggedIn());
        $this->assertInstanceOf(\Magento\Backend\Model\Auth::class, $this->_auth);

        $this->dispatch($this->uri);

        /** @var \Magento\Framework\App\Response\Http $response */
        $response = $this->getResponse();
        $this->assertSame(200, $response->getHttpResponseCode());

        $body = $response->getBody();
        $this->assertNotEmpty($body);
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

        $response = $this->getResponse();
        $this->assertSame(302, $response->getHttpResponseCode());
    }
}
