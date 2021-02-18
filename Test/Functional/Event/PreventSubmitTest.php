<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Functional\Event;

use Magento\Framework\Exception\LocalizedException;
use Yireo\SalesBlock2\Observer\PreventSaveOrder;

class PreventSubmitTest extends AbstractTestCase
{
    public function testEventExceptionWithMatchingRule()
    {
        $this->setRule('Dummy', ['name' => 'email', 'value' => 'john@example.com']);

        $event = $this->createEvent();
        $event->setOrder($this->getOrder('john@example.com'));
        $observer = $this->createObserver($event);

        $eventObserver = $this->getObjectManager()->get(PreventSaveOrder::class);

        $this->expectException(LocalizedException::class);
        $eventObserver->execute($observer);
    }

    public function testEventExceptionWithNoMatchingRule()
    {
        $this->setRule('Dummy', ['name' => 'email', 'value' => 'jane@example.com']);

        $event = $this->createEvent();
        $event->setOrder($this->getOrder('john@example.com'));
        $observer = $this->createObserver($event);

        $eventObserver = $this->getObjectManager()->get(PreventSaveOrder::class);
        $eventObserver->execute($observer);
        $this->assertTrue(true);
    }
}
