<?php

declare(strict_types=1);

namespace LiteDocs\Core;

use Symfony\Component\Yaml\Yaml;

final class Translator
{
    public function __construct(
        private string $projectDir,
        private string $themeDir,
        private string $themeName,
    ) {
    }

    public function getTranslations(string $lang): array
    {
        $corePath = dirname(__DIR__) . '/Resources/translations';
        $coreData = ['core' => $this->loadIds($corePath, $lang)];

        $themePath = $this->themeDir . '/translations';
        $themeData = [$this->themeName => $this->loadIds($themePath, $lang)];

        $userPath = $this->projectDir . '/translations';
        $userData = $this->loadIds($userPath, $lang);

        return array_replace_recursive($coreData, $themeData, $userData);
    }

    private function loadIds(string $dir, string $lang): array
    {
        $data = [];
        $enFile = $dir . '/en.yaml';

        if (file_exists($enFile)) {
            $data = Yaml::parseFile($enFile) ?? [];
        }

        if ($lang !== 'en') {
            $langFile = $dir . '/' . $lang . '.yaml';
            if (file_exists($langFile)) {
                $specific = Yaml::parseFile($langFile) ?? [];
                $data = array_replace_recursive($data, $specific);
            }
        }

        return $data;
    }
}
