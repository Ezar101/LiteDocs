# Welcome to LiteDocs

**LiteDocs** is a lightweight, modern static site generator written in PHP. It is designed to be fast, extensible, and easy to use for technical documentation.

## Why LiteDocs?

* **⚡ Fast:** Generates static HTML files in seconds.
* **🔌 Extensible:** Plugin system to add features easily.
* **🎨 Theming:** Modern default theme (Sylius/GitBook style).
* **🌍 Multilingual:** Native support for multiple languages.
* **🔍 Search:** Built-in client-side search engine.

## Quick Start

Run the following command to build your documentation:

```bash
php bin/litedocs build
```

## Quick Example

Here is how you initialize the kernel in PHP:

```php
use LiteDocs\Core\Kernel;

$kernel = new Kernel(__DIR__);
$kernel->boot();
$kernel->build();

<div data-search-ignore style="background: #e0f7fa; color: #006064; padding: 15px; border-radius: 4px; margin-top: 20px;"> <strong>Tip:</strong> This block uses the <code>data-search-ignore</code> attribute. It will be visible to the user but ignored by the search bar indexer. </div>
```
