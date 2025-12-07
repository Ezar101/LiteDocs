# Theming

LiteDocs uses **Twig** as a templating engine. You can easily customize the look and feel.

## Creating a Theme

1. Create a folder in `themes/` (e.g., `themes/my-theme`).
2. Create a `base.html.twig` file inside.
3. Create an `assets/` folder for your CSS and JS.

## Available Variables

In your Twig templates, you have access to:

* `content`: The HTML content of the page.
* `nav`: The navigation tree.
* `page_title`: The current page title.
* `config`: The full configuration array.
* `trans`: Translations (based on current language).

## Overriding Translations

You can override any text by creating a `translations/en.yaml` file at the root of your project:

```yaml
core:
    ui:
        navigation: "Main Menu"
```
