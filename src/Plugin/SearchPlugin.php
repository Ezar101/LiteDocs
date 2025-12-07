<?php

declare(strict_types=1);

namespace LiteDocs\Plugin;

use LiteDocs\Event\GenericEvent;
use LiteDocs\Event\PageEvent;

class SearchPlugin extends AbstractPlugin
{
    private array $index = [];

    public function onAfterParse(PageEvent $event): void
    {
        $html = $event->content;
        $context = $event->getContext();
        $title = 'Untitled';

        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/si', $html, $matches)) {
            $title = strip_tags($matches[1]);
        } elseif (!empty($context['page_title'])) {
            $title = $context['page_title'];
        }

        $htmlCleaned = preg_replace('/<([a-z]+)[^>]*data-search-ignore[^>]*>.*?<\/\1>/si', '', $html);

        $text = str_replace(["\n", "\r", "\t"], ' ', strip_tags($htmlCleaned));
        $text = preg_replace('/\s+/', ' ', $text);
        $text = substr($text, 0, 1000) . '...';

        $url = $context['public_url'] ?? $event->getPath();

        $this->index[] = [
            'title' => trim($title),
            'text'  => $text,
            'url'   => $url,
        ];

        $rootPath = $context['root_path'] ?? './';

        $event->addJs(__DIR__ . '/Resources/search.js');

        $scriptIndex = '<script src="' . $rootPath . 'search_index.js"></script>';
        $event->content = $event->content . "\n" . $scriptIndex;
    }

    public function onShutdown(GenericEvent $event): void
    {
        $config = $event->config;
        $siteDir = $config['site_dir'];

        $json = json_encode($this->index, JSON_UNESCAPED_UNICODE);
        $jsContent = "window.PHP_DOCS_SEARCH_INDEX = " . $json . ";";

        if (!is_dir($siteDir)) {
            mkdir($siteDir, 0777, true);
        }

        file_put_contents($siteDir . '/search_index.js', $jsContent);
    }
}
