<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration;

use Magento\Framework\Acl\Builder;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class AclTest extends TestCase
{
    /**
     * @magentoAppArea adminhtml
     */
    public function testConfigResourceExists(): void
    {
        $acl = Bootstrap::getObjectManager()->get(Builder::class)->getAcl();
        $this->assertTrue($acl->hasResource('Yireo_SalesBlock2::config'));
        $this->assertTrue($acl->hasResource('Yireo_SalesBlock2::rules'));
    }
}
