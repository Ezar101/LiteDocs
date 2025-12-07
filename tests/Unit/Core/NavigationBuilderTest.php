<?php

declare(strict_types=1);

use LiteDocs\Core\NavigationBuilder;

test('navigation builder converts flat config to tree', function () {
    $builder = new NavigationBuilder();

    $config = [
        'Introduction' => 'index.md',
        'Guide' => [
            'Installation' => 'install.md'
        ]
    ];

    expect($builder)->toBeInstanceOf(NavigationBuilder::class);
});

test('getFlatList flattens a tree correctly', function () {
    $builder = new NavigationBuilder();

    $tree = [
        [
            'title' => 'Home',
            'url' => 'index.html',
        ],
        [
            'title' => 'Guide',
            'children' => [
                [
                    'title' => 'Install',
                    'url' => 'install.html'
                ]
            ]
        ]
    ];

    $flat = $builder->getFlatList($tree);

    expect($flat)->toBeArray();
    expect($flat)->toHaveCount(2);
    expect($flat[0]['url'])->toBe('index.html');
    expect($flat[1]['url'])->toBe('install.html');
});
