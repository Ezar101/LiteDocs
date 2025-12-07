<?php

declare(strict_types=1);

namespace LiteDocs\Plugin;

use LiteDocs\Event\BuildEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use LiteDocs\Event\GenericEvent;
use LiteDocs\Event\PageEvent;

/**
 * @method void onStartup(GenericEvent $event)
 * @method void onBeforeParse(PageEvent $event)
 * @method void onAfterParse(PageEvent $event)
 * @method void onShutdown(GenericEvent $event)
 */
abstract class AbstractPlugin implements EventSubscriberInterface
{
    protected array $configuration = [];

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public static function getSubscribedEvents(): array
    {
        $listeners = [];

        if (method_exists(static::class, 'onStartup')) {
            $listeners[BuildEvents::ON_STARTUP] = 'onStartup';
        }

        if (method_exists(static::class, 'onBeforeParse')) {
            $listeners[BuildEvents::BEFORE_PARSE] = 'onBeforeParse';
        }

        if (method_exists(static::class, 'onAfterParse')) {
            $listeners[BuildEvents::AFTER_PARSE] = 'onAfterParse';
        }

        if (method_exists(static::class, 'onShutdown')) {
            $listeners[BuildEvents::ON_SHUTDOWN] = 'onShutdown';
        }

        return $listeners;
    }
}
