<?php

/*
 * This file is part of the aether/aether.
 *
 * Copyright (C) 2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Aether\Events;

use Psr\EventDispatcher\StoppableEventInterface;

class Event implements StoppableEventInterface
{
    protected bool $propagationStopped = false;

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    /**
     * Sets the event propagation should be stopped.
     *
     * @return void
     */
    public function setPropagationStopped(): void
    {
        $this->propagationStopped = true;
    }
}
