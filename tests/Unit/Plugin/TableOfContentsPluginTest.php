<?php

declare(strict_types=1);

use LiteDocs\Event\PageEvent;
use LiteDocs\Plugin\TableOfContentsPlugin;

test('toc plugin extracts h2 headers and generates ids', function () {
    $plugin = new TableOfContentsPlugin();

    $html = '<h1>Main Title</h1> <h2>Introduction</h2> <p>Text</p> <h2>Conclusion</h2>';

    $event = new PageEvent($html, 'test.md', []);

    $plugin->onAfterParse($event);

    $newHtml = $event->content;

    expect($newHtml)->toContain('id="introduction"');
    expect($newHtml)->toContain('id="conclusion"');

    $context = $event->getContext();

    expect($context)->toHaveKey('toc');
    expect($context['toc'])->toBeArray();
    expect($context['toc'])->toHaveCount(2);

    expect($context['toc'][0]['title'])->toBe('Introduction');
    expect($context['toc'][0]['url'])->toBe('#introduction');

    expect($context['toc'][1]['title'])->toBe('Conclusion');
    expect($context['toc'][1]['url'])->toBe('#conclusion');
});

test('toc plugin handles duplicate headers correctly', function () {
    $plugin = new TableOfContentsPlugin();

    $html = '<h2>Installation</h2> <p>...</p> <h2>Installation</h2>';

    $event = new PageEvent($html, 'test.md', []);
    $plugin->onAfterParse($event);

    $context = $event->getContext();

    expect($context['toc'][0]['url'])->toBe('#installation');

    expect($context['toc'][1]['url'])->not->toBe('#installation');
    expect($context['toc'][1]['url'])->toContain('installation-');
});
