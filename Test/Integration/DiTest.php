<?php
namespace Yireo\SalesBlock2\Test\Integration;

use Magento\TestFramework\Helper\Bootstrap;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Yireo\SalesBlock2\Model\Rule\Repository as RuleRepository;

/**
 * Class DiTest
 *
 * @package Yireo\SalesBlock2\Test\Integration
 */
class DiTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test whether fetching the preferences work
     */
    public function testPreferences()
    {
        /** @var RuleRepositoryInterface $ruleRepository */
        $ruleRepository = Bootstrap::getObjectManager()->create(RuleRepositoryInterface::class);
        $this->assertInstanceOf(RuleRepository::class, $ruleRepository);
    }
}
