<?php

declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Adminhtml;

use Magento\Framework\Api\Search\AggregationInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Model\ResourceModel\Rule\Grid\Collection;

class GridCollectionTest extends TestCase
{
    public function testSetAggregationsReturnsCollection()
    {
        /** @var Collection $collection */
        $collection = Bootstrap::getObjectManager()->create(Collection::class);
        $aggregations = $this->createMock(AggregationInterface::class);

        $this->assertSame($collection, $collection->setAggregations($aggregations));
        $this->assertSame($aggregations, $collection->getAggregations());
    }
}
