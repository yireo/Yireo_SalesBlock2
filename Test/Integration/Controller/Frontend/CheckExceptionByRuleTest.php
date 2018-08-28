<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Controller\Frontend;

use Magento\TestFramework\TestCase\AbstractController;
use Zend\Http\Request;

/**
 * Class CheckExceptionByRuleTest
 *
 * @package Yireo\SalesBlock2\Test\Integration\Controller\Frontend
 */
class CheckExceptionByRuleTest extends AbstractController
{
    /**
     * @magentoAppArea frontend
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testIfAddToCartIsAllowed()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $request->setMethod(Request::METHOD_POST);

        $data = [
            'product' => 1
        ];

        $post = new \Zend\Stdlib\Parameters($data);
        $request->setPost($post);
        $this->dispatch('checkout/cart/add');

        /** @var \Magento\Checkout\Model\Session $checkoutSession */
        $checkoutSession = $this->_objectManager->get(\Magento\Checkout\Model\Session::class);
        $quote = $checkoutSession->getQuote();
        $items = $quote->getItems();
        $this->assertNotEmpty($items);
    }
}