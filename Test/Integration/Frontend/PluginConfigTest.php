<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Frontend;

use Magento\Framework\Interception\PluginList\PluginList;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Plugin\PreventAddToQuote;

class PluginConfigTest extends TestCase
{
    /**
     * @magentoAppArea frontend
     */
    public function testPreventAddToQuoteTargetsQuoteModel(): void
    {
        $pluginList = Bootstrap::getObjectManager()->get(PluginList::class);
        $plugins = $pluginList->get(Quote::class, []);
        $this->assertArrayHasKey('salesblock_prevent_add_quote', $plugins);
        $this->assertSame(PreventAddToQuote::class, $plugins['salesblock_prevent_add_quote']['instance']);

        $interfacePlugins = $pluginList->get(CartInterface::class, []);
        $this->assertArrayNotHasKey('salesblock_prevent_add_quote', $interfacePlugins);
    }
}
