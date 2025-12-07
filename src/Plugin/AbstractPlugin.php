<?php

declare(strict_types=1);

namespace LiteDocs\Plugin;

use LiteDocs\Event\BuildEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use LiteDocs\Event\GenericEvent;
use LiteDocs\Event\PageEvent;

abstract class AbstractPlugin implements EventSubscriberInterface
{
    protected array $configuration = [];

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BuildEvents::ON_STARTUP   => 'onStartup',
            BuildEvents::BEFORE_PARSE => 'onBeforeParse',
            BuildEvents::AFTER_PARSE  => 'onAfterParse',
            BuildEvents::ON_SHUTDOWN  => 'onShutdown',
        ];
    }

    public function onStartup(GenericEvent $event): void
    {
    }

    public function onBeforeParse(PageEvent $event): void
    {
    }

    public function onAfterParse(PageEvent $event): void
    {
    }

    public function onShutdown(GenericEvent $event): void
    {
    }
}
