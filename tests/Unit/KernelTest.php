<?php

declare(strict_types=1);

use LiteDocs\Core\Kernel;
use Symfony\Component\EventDispatcher\EventDispatcher;

test('application has a version', function () {
    expect(Kernel::VERSION)->toBeString();
    expect(Kernel::VERSION)->not->toBeEmpty();
});

test('kernel can verify theme directory', function () {
    $kernel = new Kernel(__DIR__, new EventDispatcher());

    $reflection = new ReflectionClass($kernel);
    $method = $reflection->getMethod('resolveThemeDirectory');
    $method->setAccessible(true);

    $path = $method->invoke($kernel, 'lite');

    expect($path)->toBeDirectory();
    expect($path)->toContain('lite');
});
