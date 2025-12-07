<?php

declare(strict_types=1);

use LiteDocs\Core\Translator;

test('translator can be instantiated', function () {
    $translator = new Translator(__DIR__, __DIR__, 'lite');

    expect($translator)->toBeInstanceOf(Translator::class);
});

test('translator returns array', function () {
    $translator = new Translator(__DIR__, __DIR__, 'lite');
    $result = $translator->getTranslations('en');

    expect($result)->toBeArray();
});
