<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Logger;

use Magento\Framework\Logger\Handler\Base;
use Magento\TestFramework\Helper\Bootstrap;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Yireo\SalesBlock2\Logger\Debugger;

class DebuggerTest extends TestCase
{
    /**
     * @magentoAppArea adminhtml
     */
    public function testAdminUsesOnlyDedicatedLogFile(): void
    {
        $this->assertOnlyDedicatedHandler();
    }

    /**
     * @magentoAppArea frontend
     */
    public function testFrontendUsesOnlyDedicatedLogFile(): void
    {
        $this->assertOnlyDedicatedHandler();
    }

    private function assertOnlyDedicatedHandler(): void
    {
        $debugger = Bootstrap::getObjectManager()->create(Debugger::class);
        $property = new ReflectionProperty(Debugger::class, 'logger');
        $logger = $property->getValue($debugger);

        $this->assertInstanceOf(Logger::class, $logger);
        $handlers = $logger->getHandlers();
        $this->assertCount(1, $handlers);
        $this->assertInstanceOf(Base::class, $handlers[0]);
        $this->assertStringEndsWith('yireo_salesblock.log', (string)$handlers[0]->getUrl());
    }
}
