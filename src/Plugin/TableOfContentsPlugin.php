<?php

declare(strict_types=1);

namespace LiteDocs\Plugin;

use LiteDocs\Event\PageEvent;

class TableOfContentsPlugin extends AbstractPlugin
{
    public function onAfterParse(PageEvent $event): void
    {
        $html = $event->content;
        $toc = [];

        if (preg_match_all('/<h2>(.*?)<\/h2>/s', $html, $matches)) {
            foreach ($matches[1] as $index => $title) {
                $title = strip_tags($title);
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

                if (empty($slug) || isset($toc[$slug])) {
                    $slug .= '-' . $index;
                }

                $pattern = '/<h2>' . preg_quote($matches[1][$index], '/') . '<\/h2>/';
                $replacement = '<h2 id="' . $slug . '">' . $matches[1][$index] . '</h2>';
                $html = preg_replace($pattern, $replacement, $html, 1);

                $toc[] = [
                    'title' => $title,
                    'url'   => '#' . $slug,
                ];
            }
        }

        $event->content = $html;
        $event->addToContext('toc', $toc);
    }
}
