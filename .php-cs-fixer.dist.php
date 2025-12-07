<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true, // Standard officiel PHP
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);
