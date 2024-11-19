<?php

/*
 * This file is part of the aether/aether.
 *
 * Copyright (C) 2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Aether\Tests\Events;

use Aether\Events\Event;
use Aether\Events\EventDispatcher;
use Aether\Events\EventDispatcherInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EventDispatcher::class)]
class EventDispatcherTest extends TestCase
{
    private EventDispatcherInterface $dispatcher;

    protected function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
    }

    public function testDispatchesWithoutListeners(): void
    {
        $listeners = $this->dispatcher->getListeners('event');

        self::assertEmpty($listeners);
        self::assertCount(0, $listeners);
    }

    public function testDispatchShouldInvokeListener(): void
    {
        $invoked = false;

        $this->dispatcher->listen('test.event', function () use (&$invoked) {
            $invoked = true;
        });

        $this->dispatcher->dispatch(new class () {
        }, 'test.event');

        self::assertTrue($invoked, 'Listener should be invoked on event dispatch.');
    }

    public function testDispatchShouldNotInvokeListenerAfterPropagationStopped(): void
    {
        $invokedAfterStop = false;

        $this->dispatcher->listen('test.event', function (Event $event) {
            $event->setPropagationStopped();
        });

        $this->dispatcher->listen('test.event', function () use (&$invokedAfterStop) {
            $invokedAfterStop = true;
        });

        $this->dispatcher->dispatch(new Event(), 'test.event');

        self::assertFalse($invokedAfterStop, 'Listener should not be invoked after propagation is stopped');
    }
}
