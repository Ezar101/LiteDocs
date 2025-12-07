<?php

declare(strict_types=1);

namespace LiteDocs\Core;

use Symfony\Component\Finder\Finder;

class NavigationBuilder
{
    public function build(array $config, Finder $finder, ?string $currentLang = null): array
    {
        if (!empty($config['nav'])) {
            if ($currentLang && isset($config['nav'][$currentLang])) {
                return $this->buildFromConfig($config['nav'][$currentLang]);
            }

            if (isset($config['nav'][0])) {
                return $this->buildFromConfig($config['nav']);
            }

            return $this->buildFromConfig($config['nav']);
        }

        return $this->buildFromAuto($finder);
    }

    public function getFlatList(array $tree): array
    {
        $flat = [];

        foreach ($tree as $item) {
            if (!empty($item['url'])) {
                $flat[] = [
                    'title' => $item['title'],
                    'url'   => $item['url'],
                ];
            }

            if (!empty($item['children'])) {
                $flat = array_merge($flat, $this->getFlatList($item['children']));
            }
        }

        return $flat;
    }

    private function buildFromConfig(array $navConfig): array
    {
        $tree = [];

        foreach ($navConfig as $item) {
            foreach ($item as $title => $value) {
                if (is_array($value)) {
                    $tree[] = [
                        'title' => $title,
                        'url'   => null,
                        'children' => $this->buildFromConfig($value),
                    ];
                }
                else {
                    $cleanPath = str_replace('\\', '/', $value);
                    $url = str_replace('.md', '.html', $cleanPath);

                    $tree[] = [
                        'title' => $title,
                        'url'   => $url,
                        // No children here
                    ];
                }
            }
        }

        return $tree;
    }

    private function buildFromAuto(Finder $files): array
    {
        $tree = [];

        foreach ($files as $file) {
            $relativePath = str_replace('\\', '/', $file->getRelativePathname());
            $url = str_replace('.md', '.html', $relativePath);

            $parts = explode('/', $relativePath);
            $filename = array_pop($parts);

            $title = ucfirst(str_replace(['.md', '-', '_'], ['', ' ', ' '], $filename));

            if ($filename === 'index.md' && empty($parts)) {
                $title = 'Home';
            }

            $this->addToTree($tree, $parts, $title, $url);
        }

        return $tree;
    }

    private function addToTree(array &$level, array $parts, string $title, string $url): void
    {
        if (empty($parts)) {
            $level[] = [
                'title' => $title,
                'url'   => $url,
            ];

            return;
        }

        $head = array_shift($parts);
        $dirTitle = ucfirst($head);

        $foundIndex = null;
        foreach ($level as $index => $node) {
            if (isset($node['children']) && $node['title'] === $dirTitle) {
                $foundIndex = $index;
                break;
            }
        }

        if ($foundIndex === null) {
            $level[] = [
                'title' => $dirTitle,
                'url'   => null,
                'children' => [],
            ];

            $foundIndex = count($level) - 1;
        }

        $this->addToTree($level[$foundIndex]['children'], $parts, $title, $url);
    }
}
