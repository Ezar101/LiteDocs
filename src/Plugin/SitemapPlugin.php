<?php

declare(strict_types=1);

namespace LiteDocs\Plugin;

use LiteDocs\Event\GenericEvent;
use LiteDocs\Event\PageEvent;

final class SitemapPlugin extends AbstractPlugin
{
    private array $urls = [];

    public function onAfterParse(PageEvent $event): void
    {
        $context = $event->getContext();

        if (isset($context['public_url'])) {
            $this->urls[] = $context['public_url'];
        }
    }

    public function onShutdown(GenericEvent $event): void
    {
        $config = $event->config;
        $siteDir = $config['site_dir'];

        if (empty($config['site_url'])) {
            echo "Warning: 'site_url' is missing in configuration. Sitemap generation skipped.\n";

            return;
        }

        $baseUrl = rtrim($config['site_url'], '/');
        $today = date('Y-m-d');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($this->urls as $path) {
            $fullUrl = $baseUrl . '/' . ltrim($path, '/');

            $xml .= "    <url>\n";
            $xml .= "        <loc>{$fullUrl}</loc>\n";
            $xml .= "        <lastmod>{$today}</lastmod>\n";
            $xml .= "        <changefreq>daily</changefreq>\n";
            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>';

        if (!is_dir($siteDir)) {
            mkdir($siteDir, 0777, true);
        }

        file_put_contents($siteDir . '/sitemap.xml', $xml);

        echo "Sitemap generated at {$siteDir}/sitemap.xml\n";
    }
}
