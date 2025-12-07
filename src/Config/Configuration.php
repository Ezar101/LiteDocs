<?php

declare(strict_types=1);

namespace LiteDocs\Config;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Configuration
{
    public function resolve(array $config): array
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
            'site_name' => 'My LiteDocs',
            'site_url'  => null,
            'docs_dir'  => 'docs',
            'site_dir'  => 'site',
            'theme'     => ['name' => 'lite'],
            'plugins'   => [],
            'nav'       => null,
            'languages' => [],
            'logo'      => null,
            'favicon'   => null,
        ]);

        $resolver->setAllowedTypes('site_name', 'string');
        $resolver->setAllowedTypes('site_url', ['null', 'string']);
        $resolver->setAllowedTypes('docs_dir', 'string');
        $resolver->setAllowedTypes('site_dir', 'string');
        $resolver->setAllowedTypes('theme', ['string', 'array']);
        $resolver->setAllowedTypes('plugins', 'array');
        $resolver->setAllowedTypes('nav', ['null', 'array']);
        $resolver->setAllowedTypes('languages', 'array');
        $resolver->setAllowedTypes('logo', ['null', 'string']);
        $resolver->setAllowedTypes('favicon', ['null', 'string']);

        $resolver->setNormalizer('theme', function (Options $options, string | array $value): array {
            if (is_string($value)) {
                return ['name' => $value];
            }

            return $value;
        });

        return $resolver->resolve($config);
    }
}
