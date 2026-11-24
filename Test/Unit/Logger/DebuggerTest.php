<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Unit\Logger;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Yireo\SalesBlock2\Configuration\Configuration;
use Yireo\SalesBlock2\Logger\Debugger;

class DebuggerTest extends TestCase
{
    public function testDebugStripsLineBreaksAndMasksEmails(): void
    {
        $config = $this->createMock(Configuration::class);
        $config->method('isLogging')->willReturn(true);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('notice')
            ->with('Current email: j***@example.org [2026] FAKE ENTRY');

        $debugger = new Debugger($config, $logger);
        $this->assertTrue($debugger->debug("Current email: john.doe@example.org\r\n[2026] FAKE ENTRY"));
    }

    public function testDebugIsSkippedWhenLoggingIsDisabled(): void
    {
        $config = $this->createMock(Configuration::class);
        $config->method('isLogging')->willReturn(false);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('notice');

        $this->assertFalse((new Debugger($config, $logger))->debug('foo'));
    }
}
