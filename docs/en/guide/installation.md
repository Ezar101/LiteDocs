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
wget [https://github.com/ezar101/litedocs/releases/latest/download/litedocs.phar](https://github.com/ezar101/litedocs/releases/latest/download/litedocs.phar)

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
