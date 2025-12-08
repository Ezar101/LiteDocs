# Installation

## Requirements

Before using LiteDocs, ensure your environment meets these requirements:

* **PHP:** 8.4 or higher
* **Composer:** installed globally (optional if using PHAR)
* **Extensions:** `php-xml`, `php-mbstring`

## Option 1: Using the PHAR (Recommended)

You can download the standalone executable directly. This is the easiest way to get started.

```bash
# Download the latest release
wget https://github.com/ezar101/litedocs/releases/latest/download/litedocs.phar

# Make it executable
chmod +x litedocs.phar

# Move to bin (optional)
sudo mv litedocs.phar /usr/local/bin/litedocs
```
## Option 2: Using Composer

You can also install it as a global dependency:

```bash
composer global require ezar101/litedocs
```

## Development Workflow

When writing documentation, you don't want to run the build command manually every time.

Use the **watch** command to watch for file changes in your `docs` and `themes` directories:

```bash
litedocs watch
```

## Live Preview

Since LiteDocs is a static site generator, it doesn't include a web server. To preview your site locally:

1. Open a terminal and run the watcher: `litedocs watch`
2. Open a second terminal and run PHP's built-in server:

    ```bash
    php -S localhost:8000 -t site
    ```
3. Go to `http://localhost:8000` in your browser.
