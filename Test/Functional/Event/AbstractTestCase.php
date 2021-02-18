<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Functional\Event;

use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Yireo\SalesBlock2\Test\Functional\AbstractTestCase as ParentAbstractTestCase;

class AbstractTestCase extends ParentAbstractTestCase
{
    /**
     * @param Event $event
     * @return Observer
     */
    protected function createObserver(Event $event): Observer
    {
        $observer = $this->getObjectManager()->create(Observer::class);
        $observer->setEvent($event);
        return $observer;
    }

    /**
     * @param string $eventName
     * @return Event
     */
    protected function createEvent(string $eventName = 'foobar'): Event
    {
        $event = $this->getObjectManager()->create(Event::class);
        $event->setName($eventName);
        return $event;
    }
}
