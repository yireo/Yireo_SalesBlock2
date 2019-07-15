<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Controller\Frontend;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use Magento\TestFramework\TestCase\AbstractController;

use Yireo\SalesBlock2\Test\Integration\RuleProvider;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
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
     * @magentoConfigFixture current_store salesblock/settings/enabled 1
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @throws LocalizedException
     */
    public function testIfAddToCartIsStillWorkingd()
    {
        $ruleProvider = ObjectManager::getInstance()->get(RuleProvider::class);
        $ruleProvider->createRule('ip', '127.0.0.1', true);
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
        $this->assertNotEmpty($this->getResponse()->getBody());
    }

    /**
     * @return ProductInterface
     */
    private function fetchProduct(): ProductInterface
    {
        $productRepository = ObjectManager::getInstance()->get(ProductRepositoryInterface::class);
        return $productRepository->get('simple');
    }
}
