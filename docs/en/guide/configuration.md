# Configuration

LiteDocs uses a single YAML file named `litedocs.yml` at the root of your project.

## Basic Structure

Here is a complete example of a configuration file:

```yaml
site_name: "My Documentation"
site_url: "[https://example.com](https://example.com)"

# Directories
docs_dir: "docs"
site_dir: "site"

# Branding
logo: "assets/logo.png"
favicon: "assets/favicon.ico"

# Theme
theme:
    name: lite

# Imports (Recommended for large projects)
nav: "config/nav.yml"
plugins: "config/plugins.yml"
```

## Navigation Configuration

You can separate your navigation logic into `config/nav.yml`:


```yaml
en:
    - Home: index.md
    - Guide:
        - Setup: guide/installation.md
```
