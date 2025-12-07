<?php

declare(strict_types=1);

namespace LiteDocs\Plugin;

use LiteDocs\Event\PageEvent;

class ReadingTimePlugin extends AbstractPlugin
{
    public function onBeforeParse(PageEvent $event): void
    {
        $content = $event->content;
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / 200);

        $badge = sprintf(
            '<div data-search-ignore style="background: #e0f7fa; color: #006064; padding: 10px; margin-bottom: 20px; border-radius: 4px; font-size: 0.9em;">' .
            '⏱️ Temps de lecture estimé : <strong>%d min</strong>' .
            '</div>',
            $minutes,
        );

        $event->content = $badge . "\n\n" . $content;
    }
}
