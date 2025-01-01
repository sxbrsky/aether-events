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

use Psr\EventDispatcher\EventDispatcherInterface as BaseEventDispatcher;

interface EventDispatcherInterface extends BaseEventDispatcher
{
    /**
     * Registers an event listener with the event dispatcher.
     *
     * @param string $eventName The event name.
     * @param callable $listener A listener for given event.
     * @param int $priority The higher the priority, the earlier the listener will be triggered.
     */
    public function listen(string $eventName, callable $listener, int $priority = 10): void;

    /**
     * @template T of object
     *
     * @param T $event The object to process.
     * @param null|string $eventName The event name.
     * @return T The passed $event MUST be returned.
     */
    public function dispatch(object $event, ?string $eventName = null): object;

    /**
     * Gets listeners for given event.
     *
     * @param string $eventName The event name.
     * @return callable[] Returns a sorted array of listeners.
     */
    public function getListeners(string $eventName): iterable;
}
