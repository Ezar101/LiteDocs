<?php

declare(strict_types=1);

namespace LiteDocs\Core;

use League\CommonMark\CommonMarkConverter;
use LiteDocs\Config\Configuration;
use LiteDocs\Event\BuildEvents;
use LiteDocs\Event\GenericEvent;
use LiteDocs\Event\PageEvent;
use LiteDocs\Plugin\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Kernel
{
    public const string VERSION = '1.0.2';

    private array $config;

    private Filesystem $filesystem;

    public function __construct(
        private string $projectDir,
        private EventDispatcherInterface $dispatcher,
    ) {
        $this->filesystem = new Filesystem();
    }

    public function boot(): void
    {
        $configFile = $this->projectDir . '/litedocs.yaml';
        $rawConfig = file_exists($configFile) ? (Yaml::parseFile($configFile) ?? []) : [];

        $this->resolveImports($rawConfig);

        $configuration = new Configuration();
        $this->config = $configuration->resolve($rawConfig);

        $this->loadPlugins();
    }

    public function build(): void
    {
        $globalDocsDir = $this->projectDir . '/' . $this->config['docs_dir'];
        $globalSiteDir = $this->projectDir . '/' . $this->config['site_dir'];

        if ($this->filesystem->exists($globalSiteDir)) {
            $this->filesystem->remove($globalSiteDir);
        }

        $this->filesystem->mkdir($globalSiteDir);

        $versions = [];

        if (empty($this->config['languages'])) {
            $versions[] = [
                'lang' => null,
                'label' => null,
                'source' => $globalDocsDir,
                'dest' => $globalSiteDir,
            ];
        } else {
            foreach ($this->config['languages'] as $code => $label) {
                $versions[] = [
                    'lang' => $code,
                    'label' => $label,
                    'source' => $globalDocsDir . '/' . $code,
                    'dest' => $globalSiteDir . '/' . $code,
                ];
            }

            $defaultLang = array_key_first($this->config['languages']);
            $this->createRedirectPage($globalSiteDir . '/index.html', "./$defaultLang/index.html");
        }

        $templatePaths = [];

        if (is_dir($this->projectDir . '/themes')) {
            $templatePaths[] = $this->projectDir . '/themes';
        }

        $templatePaths[] = dirname(__DIR__) . '/Resources/themes';

        $loader = new FilesystemLoader($templatePaths);
        $twig = new Environment($loader, [
            'autoescape' => false,
        ]);

        $converter = new CommonMarkConverter([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);

        foreach ($versions as $version) {
            $this->buildVersion($version, $globalSiteDir, $twig, $converter);
        }

        $themeName = $this->config['theme']['name'];
        $themeDir = $this->resolveThemeDirectory($themeName);
        $themeAssetsDir = $themeDir . '/assets';

        if ($this->filesystem->exists($themeAssetsDir)) {
            $this->filesystem->mirror($themeAssetsDir, $globalSiteDir . '/assets');
        }

        $this->dispatcher->dispatch(new GenericEvent($this->config), BuildEvents::ON_SHUTDOWN);
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    private function resolveImports(array &$config): void
    {
        $importableKeys = ['nav', 'plugins'];

        foreach ($importableKeys as $key) {
            if (isset($config[$key]) && is_string($config[$key])) {
                $filePath = $this->projectDir . '/' . $config[$key];

                if (!file_exists($filePath)) {
                    throw new \RuntimeException(sprintf('The external configuration file "%s" could not be found.', $config[$key]));
                }

                $importedContent = Yaml::parseFile($filePath);

                $config[$key] = $importedContent ?? [];
            }
        }
    }

    private function loadPlugins(): void
    {
        foreach ($this->config['plugins'] as $class => $pluginConfig) {
            if (!class_exists($class)) {
                echo "Warning: Plugin '$class' not found.\n";
                continue;
            }

            $plugin = new $class();

            if ($plugin instanceof AbstractPlugin) {
                $plugin->setConfiguration($pluginConfig ?? []);
            }

            $this->dispatcher->addSubscriber($plugin);
        }
    }

    private function buildVersion(array $version, string $globalSiteDir, Environment $twig, CommonMarkConverter $converter): void
    {
        $sourceDir = $version['source'];
        $destDir = $version['dest'];
        $currentLang = $version['lang'];
        $themeName = $this->config['theme']['name'];
        $themeDir = $this->resolveThemeDirectory($themeName);

        if (!is_dir($sourceDir)) {
            echo "Warning: Source folder '$sourceDir' not found. Skipping.\n";

            return;
        }

        $this->dispatcher->dispatch(new GenericEvent($this->config), BuildEvents::ON_STARTUP);

        $translator = new Translator($this->projectDir, $themeDir, $themeName);
        $translations = $translator->getTranslations($currentLang);

        $finder = new Finder();
        $finder->files()->in($sourceDir)->name('*.md')->sortByName();

        $navBuilder = new NavigationBuilder();
        $navigation = $navBuilder->build($this->config,clone $finder, $currentLang);

        $flatNavigation = $navBuilder->getFlatList($navigation);
        $flatUrls = array_column($flatNavigation, 'url');

        foreach ($finder as $file) {
            $relativePath = str_replace('\\', '/', $file->getRelativePathname());
            $rawContent = $file->getContents();
            $currentPage = str_replace('.md', '.html', $relativePath);

            $localDepth = substr_count($relativePath, '/');
            $navPath = $localDepth > 0 ? str_repeat('../', $localDepth) : './';

            $totalDepth = $currentLang ? ($localDepth + 1) : $localDepth;
            $rootPath = $totalDepth > 0 ? str_repeat('../', $totalDepth) : './';

            $pageEvent = new PageEvent($rawContent, $relativePath, $this->config);
            $this->dispatcher->dispatch($pageEvent, BuildEvents::BEFORE_PARSE);
            $rawContent = $pageEvent->content;

            $htmlContent = $converter->convert($rawContent)->getContent();

            $filenameTitle = ucfirst(str_replace('.md', '', $file->getFilenameWithoutExtension()));
            $finalTitle = $filenameTitle;
            $breadcrumbs = [];
            $pageInfo = $this->findPageInfoInNav($navigation, $currentPage);
            $publicUrl = $currentLang ? $currentLang . '/' . $currentPage : $currentPage;

            if ($pageInfo) {
                $finalTitle = $pageInfo['title'];
                $breadcrumbs = $pageInfo['breadcrumbs'];
            } else {
                $breadcrumbs[] = ['title' => $finalTitle, 'url' => $currentPage];
            }

            if (!$pageInfo) {
                if (preg_match('/<h1[^>]*>(.*?)<\/h1>/si', $htmlContent, $matches)) {
                    $finalTitle = strip_tags($matches[1]);
                    $breadcrumbs[count($breadcrumbs)-1]['title'] = $finalTitle;
                }
            }

            $pageEvent->addToContext('root_path', $rootPath);
            $pageEvent->addToContext('page_title', $finalTitle);
            $pageEvent->addToContext('public_url', $publicUrl);

            $pageEvent->content = $htmlContent;
            $this->dispatcher->dispatch($pageEvent, BuildEvents::AFTER_PARSE);
            $htmlContent = $pageEvent->content;

            $prevPage = null;
            $nextPage = null;
            $currentIndex = array_search($currentPage, $flatUrls);

            if ($currentIndex !== false) {
                if ($currentIndex > 0) {
                    $prevPage = $flatNavigation[$currentIndex - 1];
                }

                if ($currentIndex < count($flatNavigation) - 1) {
                    $nextPage = $flatNavigation[$currentIndex + 1];
                }
            }

            $processedCss = [];
            foreach ($pageEvent->getExtraCss() as $css) {
                $processedCss[] = $this->processAsset($css, $globalSiteDir, $rootPath);
            }

            $processedJs = [];
            foreach ($pageEvent->getExtraJs() as $js) {
                $processedJs[] = $this->processAsset($js, $globalSiteDir, $rootPath);
            }

            $isSearchEnabled = false;
            foreach ($this->config['plugins'] as $pluginClass => $config) {
                if (str_contains($pluginClass, 'SearchPlugin') || (is_string($config) && str_contains($config, 'SearchPlugin'))) {
                    $isSearchEnabled = true;
                    break;
                }
            }

            $logoUrl = null;
            if (!empty($this->config['logo'])) {
                $logoUrl = $this->processAsset($this->config['logo'], $globalSiteDir, $rootPath);
            }

            $faviconUrl = null;
            if (!empty($this->config['favicon'])) {
                $faviconUrl = $this->processAsset($this->config['favicon'], $globalSiteDir, $rootPath);
            }

            $twigVariables = array_merge([
                'content'        => $htmlContent,
                'config'         => $this->config,
                'page_title'     => $finalTitle,
                'nav'            => $navigation,
                'root_path'      => $rootPath,
                'nav_path'       => $navPath,
                'current_page'   => $currentPage,
                'pagination'     => ['prev' => $prevPage, 'next' => $nextPage],
                'extra_css'      => $processedCss,
                'extra_js'       => $processedJs,
                'current_lang'   => $currentLang,
                'languages'      => $this->config['languages'],
                'trans'          => $translations ?? [],
                'search_enabled' => $isSearchEnabled,
                'breadcrumbs'    => $breadcrumbs,
                'logo_url'       => $logoUrl,
                'favicon_url'    => $faviconUrl,
                'app_version'    => self::VERSION,
            ], $pageEvent->getContext());

            $pageHtml = $twig->render($themeName . '/base.html.twig', $twigVariables);
            $outputPath = $destDir . '/' . str_replace('.md', '.html', $relativePath);

            $this->filesystem->mkdir(dirname($outputPath));
            $this->filesystem->dumpFile($outputPath, $pageHtml);
        }
    }

    private function createRedirectPage(string $path, string $target): void
    {
        $html = <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <meta http-equiv="refresh" content="0; url=$target">
                <script>window.location.href = "$target"</script>
                <title>Redirecting...</title>
            </head>
            <body>Redirecting to <a href="$target">$target</a>...</body>
            </html>
        HTML;
        $this->filesystem->dumpFile($path, $html);
    }

    private function processAsset(string $assetPath, string $siteDir, string $rootPath): string
    {
        if (filter_var($assetPath, FILTER_VALIDATE_URL)) {
            return $assetPath;
        }

        if (!file_exists($assetPath)) {
            trigger_error("Asset not found: $assetPath", E_USER_WARNING);
            return $assetPath;
        }

        $extension = pathinfo($assetPath, PATHINFO_EXTENSION);
        $filename = md5_file($assetPath) . '.' . $extension;

        $targetDir = $siteDir . '/assets/plugins';
        $targetFile = $targetDir . '/' . $filename;

        if (!$this->filesystem->exists($targetDir)) {
            $this->filesystem->mkdir($targetDir);
        }

        if (!$this->filesystem->exists($targetFile)) {
            $this->filesystem->copy($assetPath, $targetFile);
        }

        return $rootPath . 'assets/plugins/' . $filename;
    }

    private function findPageInfoInNav(array $tree, string $currentUrl, array $parents = []): ?array
    {
        foreach ($tree as $item) {
            if (isset($item['children'])) {
                $newParents = array_merge($parents, [['title' => $item['title'], 'url' => null]]);
                $result = $this->findPageInfoInNav($item['children'], $currentUrl, $newParents);

                if ($result) return $result;
            }
            elseif (isset($item['url']) && $item['url'] === $currentUrl) {
                return [
                    'title' => $item['title'],
                    'breadcrumbs' => array_merge($parents, [['title' => $item['title'], 'url' => $item['url']]]),
                ];
            }
        }

        return null;
    }

    private function resolveThemeDirectory(string $themeName): string
    {
        $userThemeDir = $this->projectDir . '/themes/' . $themeName;

        if (is_dir($userThemeDir)) {
            return $userThemeDir;
        }

        $internalThemeDir = dirname(__DIR__) . '/Resources/themes/' . $themeName;

        if (is_dir($internalThemeDir)) {
            return $internalThemeDir;
        }

        throw new \RuntimeException(sprintf('Theme "%s" not found.', $themeName));
    }
}
