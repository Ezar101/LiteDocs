<?php

declare(strict_types=1);

namespace LiteDocs\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class GenericEvent extends Event
{
    public function __construct(
        public readonly array $config,
    ) {
    }
}
