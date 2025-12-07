<?php

declare(strict_types=1);

namespace LiteDocs\Plugin;

use LiteDocs\Event\PageEvent;

class SyntaxHighlightPlugin extends AbstractPlugin
{
    public function onAfterParse(PageEvent $event): void
    {
        $event->addCss('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css');
        $event->addJs('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js');

        $config = $this->configuration;

        if (isset($config['custom_script']) && file_exists($config['custom_script'])) {
            $localScript = $config['custom_script'];
        } else {
            $localScript = __DIR__ . '/Resources/highlight-init.js';
        }

        $event->addJs($localScript);
    }
}
