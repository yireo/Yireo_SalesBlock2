<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Logger;

use Psr\Log\LoggerInterface;
use Yireo\SalesBlock2\Configuration\Configuration;

class Debugger
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Debugger constructor.
     *
     * @param Configuration $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        Configuration $config,
        LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param string $msg
     * @param mixed $data
     *
     * @return bool
     */
    public function debug(string $msg, $data = null): bool
    {
        if ($this->config->isLogging() === false) {
            return false;
        }

        if (!empty($data)) {
            $msg .= ': ' . var_export($data, true);
        }

        $this->logger->notice($this->sanitize($msg));
        return true;
    }

    /**
     * @param string $msg
     * @return string
     */
    public function sanitize(string $msg): string
    {
        $msg = (string)preg_replace('/[\x00-\x1F\x7F]+/', ' ', $msg);
        return (string)preg_replace('/([^\s@<>"\'])[^\s@<>"\']*@/', '$1***@', $msg);
    }
}
