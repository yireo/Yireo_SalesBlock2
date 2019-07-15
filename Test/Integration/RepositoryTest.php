<?php
declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration;

use Magento\TestFramework\Helper\Bootstrap;

use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Yireo\SalesBlock2\Repository\RuleRepository;

/**
 * Class RepositoryTest
 * @package Yireo\SalesBlock2\Test\Integration
 */
class RepositoryTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var RuleRepositoryInterface
     */
    private $repository;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->repository = $this->objectManager->get(RuleRepositoryInterface::class);
    }

    /**
     * Test if the repository returns multiple items
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testIfRepositoryReturnsMultipleItems()
    {
        $this->assertInstanceOf(RuleRepository::class, $this->repository);
        $items = $this->repository->getAll();
        $this->assertEmpty($items);

        $this->createRule('John Doe', 1);
        $this->createRule('Jane Doe', 1);
        $rules = $this->repository->getAll();
        $this->assertCount(2, $rules);
    }

    /**
     * Test if the repository loads, saves and deletes properly
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testIfRepositoryLoadsAndSavesAndDeletes()
    {
        $rule = $this->createRule('Foobar', 1);
        $this->assertNotEmpty($rule->getId());
        $this->assertTrue($rule->getId() > 0);
        $newRule = $this->repository->getById((int)$rule->getId());
        $this->assertSame($rule->getId(), $newRule->getId());

        $this->assertSame('Foobar', $newRule->getLabel());
        $this->assertSame(1, $newRule->getStatus());

        $newRule->setLabel('Faabor');
        $newRule->setStatus(0);
        $this->repository->save($newRule);

        $newestRule = $this->repository->getById((int)$rule->getId());
        $this->assertSame('Faabor', $newestRule->getLabel());
        $this->assertSame(0, $newestRule->getStatus());

        $this->repository->delete($newestRule);
        $items = $this->repository->getAll();
        $this->assertEmpty($items);
    }

    /**
     * Test if the repository returns multiple items
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testIfRepositoryReturnsZeroItems()
    {
        $this->assertInstanceOf(RuleRepository::class, $this->repository);
        $items = $this->repository->getAll();
        $this->assertEmpty($items);
    }

    /**
     * @param string $label
     * @param int $status
     * @return RuleInterface
     */
    private function createRule(string $label, int $status): RuleInterface
    {
        $rule = $this->repository->getEmpty();
        $rule->setLabel($label);
        $rule->setStatus($status);
        $this->repository->save($rule);
        return $rule;
    }
}
