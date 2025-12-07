<?php

declare(strict_types=1);

namespace LiteDocs\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class PageEvent extends Event
{
    private array $context = [];

    private array $extraCss = [];

    private array $extraJs = [];

    public function __construct(
        public string $content,
        private string $path,
        private array $config,
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function addToContext(string $key, mixed $value): void
    {
        $this->context[$key] = $value;
    }

    public function getExtraCss(): array
    {
        return $this->extraCss;
    }

    public function addCss(string $url): void
    {
        $this->extraCss[] = $url;
    }

    public function getExtraJs(): array
    {
        return $this->extraJs;
    }

    public function addJs(string $url): void
    {
        $this->extraJs[] = $url;
    }
}
