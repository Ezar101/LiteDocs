<p align="center">
    <img src=".assets/logo.png" alt="LiteDocs Logo" width="200">
</p>

# LiteDocs

<p align="center">
    <a href="https://github.com/Ezar101/LiteDocs/actions"><img src="https://github.com/Ezar101/LiteDocs/actions/workflows/build.yml/badge.svg" alt="Build Status"></a>
    <a href="https://github.com/Ezar101/LiteDocs/actions"><img src="https://github.com/Ezar101/LiteDocs/actions/workflows/quality.yml/badge.svg" alt="Quality Status"></a>
    <a href="https://github.com/Ezar101/LiteDocs/releases"><img src="https://img.shields.io/github/v/release/Ezar101/LiteDocs" alt="Latest Release"></a>
    <a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
</p>

**LiteDocs** is a modern, lightweight, and blazing fast static site generator written in PHP 8.4+.  
It is designed to build beautiful documentation sites from Markdown files with zero configuration.

[**📚 Read the Official Documentation**](https://Ezar101.github.io/LiteDocs/)

---

## ✨ Features

* **⚡ Zero Config:** Works out of the box with sensible defaults.
* **🎨 Theming:** Powerful templating engine based on **Twig**. Includes a modern "Lite" theme.
* **🔌 Plugins:** Extensible architecture using Symfony EventDispatcher.
* **🌍 Multilingual:** Native support for internationalization (i18n).
* **🔍 Search:** Built-in client-side search engine (no external services required).
* **🛠 Developer Friendly:** 100% PHP, easy to override and extend.

## 🚀 Installation

### Option 1: Standalone PHAR (Recommended)

You can download the single executable file from the [Releases Page](https://github.com/Ezar101/LiteDocs/releases).

```bash
wget https://github.com/Ezar101/LiteDocs/releases/latest/download/litedocs.phar
chmod +x litedocs.phar
sudo mv litedocs.phar /usr/local/bin/litedocs
```

### Option 2: Via Composer

```bash
composer global require Ezar101/litedocs
```

## 🏁 Quick Start

1. **Initialize a new project** Create a litedocs.yml file and a docs/ folder with some markdown files.
2. **Build the site** Run the build command in your project directory:

    ```bash
    litedocs build
    ```
3.  **Development Mode (Optional)**
    To automatically rebuild the site whenever you change a file:

    ```bash
    litedocs watch
    ```

    *Tip: Open a second terminal and run `php -S localhost:8000 -t site` to view your changes live.*

4. Enjoy Your static site is generated in the site/ directory, ready to be deployed to GitHub Pages, Vercel, or Netlify.

## ⚙️ Configuration

Create a `litedocs.yml` file at the root of your project:

```yaml
site_name: "My Awesome Docs"
docs_dir: "docs"
site_dir: "site"

# Enable multilingual support
languages:
    en: English
    fr: Français

# Theme configuration
theme:
    name: lite

# Import navigation and plugins
nav: "config/nav.yml"
plugins: "config/plugins.yml"
```

## 🤝 Contributing

Contributions are welcome! We enforce high code quality standards to keep the project maintainable.

### Requirements

* PHP 8.4+
* Composer

### Development Workflow

1. **Fork** the repository and create a new branch.
2. Install dependencies:

    ```bash
    composer install
    ```
3. **Write Tests**: We use [Pest PHP](https://pestphp.com/).

    ```bash
    composer test
    ```
4. **Analyze Code**: Ensure static analysis passes.

    ```bash
    composer analyse
    ```
5. **Fix Style:** Format your code to PSR-12 standards.

    ```bash
    composer lint  # Check
    composer fix   # Auto-fix
    ```
6. Submit a **Pull Request**.

**Note:** Our CI pipeline will automatically block any PR that does not pass tests or static analysis.

## 📄 License

LiteDocs is open-sourced software licensed under the [MIT license](https://www.google.com/search?q=LICENSE).