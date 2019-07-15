<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Frontend;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Exception\RuleMatchedException;
use Yireo\SalesBlock2\Plugin\PreventAddToQuote;
use Yireo\SalesBlock2\Test\Integration\RuleProvider;

/**
 * Class PreventAddToQuoteTest
 * @package Yireo\SalesBlock2\Test\Integration\Frontend
 */
class PreventAddToQuoteTest extends TestCase
{
    /**
     * @magentoAppArea frontend
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     * @magentoConfigFixture current_store salesblock/settings/enabled 1
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testPlugin()
    {
        $this->getRuleProvider()->createRule('ip', '*', true);

        /** @var PreventAddToQuote $plugin */
        $plugin = ObjectManager::getInstance()->get(PreventAddToQuote::class);
        $cart = ObjectManager::getInstance()->get(CartInterface::class);
        $productRepository = ObjectManager::getInstance()->get(ProductRepositoryInterface::class);
        $product = $productRepository->get('simple');

        $inputArguments = $plugin->beforeAddProduct($cart, $product);
        $this->assertNotEmpty($inputArguments);
    }

    /**
     * @return RuleProvider
     */
    private function getRuleProvider(): RuleProvider
    {
        return ObjectManager::getInstance()->get(RuleProvider::class);
    }
}
