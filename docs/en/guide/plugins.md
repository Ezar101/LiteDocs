# Plugins System

LiteDocs comes with a robust plugin system based on Symfony EventDispatcher.

## Built-in Plugins

We include several powerful plugins out of the box:

| Plugin Name | Description |
| :--- | :--- |
| **SearchPlugin** | Adds a client-side search engine (JS). |
| **ReadingTimePlugin** | Displays estimated reading time. |
| **TableOfContentsPlugin** | Generates a table of contents on the right side. |
| **SyntaxHighlightPlugin** | Adds colors to your code blocks using Highlight.js. |
| **SitemapPlugin** | Add sitemap.xml for the SEO. |

## Activation

To activate a plugin, add it to your `config/plugins.yml` file:

```yaml
LiteDocs\Plugin\ReadingTimePlugin: ~

LiteDocs\Plugin\SyntaxHighlightPlugin:
    custom_script: "my-custom-js.js"
```
