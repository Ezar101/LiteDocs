# Système de Plugins

LiteDocs est livré avec un système de plugins robuste basé sur Symfony EventDispatcher.

## Plugins Intégrés

Nous incluons plusieurs plugins puissants par défaut :

| Nom du Plugin | Description |
| :--- | :--- |
| **SearchPlugin** | Ajoute un moteur de recherche côté client (JS). |
| **ReadingTimePlugin** | Affiche le temps de lecture estimé. |
| **TableOfContentsPlugin** | Génère une table des matières sur le côté droit. |
| **SyntaxHighlightPlugin** | Ajoute des couleurs à vos blocs de code via Highlight.js. |
| **SitemapPlugin** | Ajoute sitemap.xml pour le SEO. |

## Activation

Pour activer un plugin, ajoutez-le à votre fichier `config/plugins.yml` :

```yaml
LiteDocs\Plugin\ReadingTimePlugin: ~

LiteDocs\Plugin\SyntaxHighlightPlugin:
    custom_script: "mon-script-js.js"
```
