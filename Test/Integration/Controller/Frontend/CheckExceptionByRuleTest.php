<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Controller\Frontend;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
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
     * @magentoConfigFixture current_store checkout/cart/redirect_to_cart 0
     * @magentoAppIsolation enabled
     * @throws LocalizedException
     */
    public function testIfAddToCartIsAllowed()
    {
        $formKey = $this->_objectManager->get(FormKey::class);

        /** @var Request $request */
        $request = $this->getRequest();
        $request->setMethod(Request::METHOD_POST);

        $data = [
            'qty' => '1',
            'product' => $this->fetchProduct()->getId(),
            'custom_price' => 1,
            'form_key' => $formKey->getFormKey(),
            'isAjax' => 1
        ];

        Bootstrap::getInstance()->loadArea('frontend');
        $request->setPostValue($data);
        $this->dispatch('checkout/cart/add');

        $this->assertFalse($this->getResponse()->isRedirect());
        $this->assertEquals('[]', $this->getResponse()->getBody());

        /** @var CheckoutSession $checkoutSession */
        $checkoutSession = $this->_objectManager->get(CheckoutSession::class);
        $quote = $checkoutSession->getQuote();
        $items = $quote->getItemsCollection();
        $this->assertNotEmpty($items->count());
    }

    /**
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    private function fetchProduct(): ProductInterface
    {
        return $this->getProductRepository()->get('simple');
    }

    /**
     * @return ProductRepositoryInterface
     */
    private function getProductRepository(): ProductRepositoryInterface
    {
        return ObjectManager::getInstance()->get(ProductRepositoryInterface::class);
    }
}
