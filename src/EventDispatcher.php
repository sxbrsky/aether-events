<?php

/*
 * This file is part of the aether/aether.
 *
 * Copyright (C) 2024-2025 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Aether\Events;

use PHPUnit\Framework\Attributes\CoversClass;
use Psr\EventDispatcher\StoppableEventInterface;

#[CoversClass(EventDispatcher::class)]
class EventDispatcher implements EventDispatcherInterface
{
    /** @var callable[][][] */
    protected array $listeners = [];

    /** @var callable[][] */
    protected array $sortedListeners = [];

    public function listen(string $eventName, callable $listener, int $priority = 10): void
    {
        $this->listeners[$eventName][$priority][] = $listener;
        unset($this->sortedListeners[$eventName]);
    }

    public function dispatch(object $event, ?string $eventName = null): object
    {
        $eventName ??= $event::class;

        if (($listeners = $this->getListeners($eventName)) !== []) {
            $this->invokeListeners($listeners, $event, $eventName);
        }

        return $event;
    }

    public function getListeners(string $eventName): iterable
    {
        if (! isset($this->listeners[$eventName])) {
            return [];
        }

        if (! isset($this->sortedListeners[$eventName])) {
            $this->sortListeners($eventName);
        }

        return $this->sortedListeners[$eventName];
    }

    /**
     * @param callable[] $listeners The event listeners.
     * @param object $event The event object to pass to the listener.
     * @param string $eventName The event name.
     */
    protected function invokeListeners(iterable $listeners, object $event, string $eventName): void
    {
        foreach ($listeners as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }

            $listener($event, $eventName, $this);
        }
    }

    /**
     * Sorts the event listeners by priority.
     */
    protected function sortListeners(string $eventName): void
    {
        \ksort($this->listeners[$eventName]);
        $this->sortedListeners[$eventName] = [];

        foreach ($this->listeners[$eventName] as &$listeners) {
            foreach ($listeners as &$listener) {
                $this->sortedListeners[$eventName][] = $listener;
            }
        }
    }
}
