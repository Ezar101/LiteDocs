# Configuration

LiteDocs utilise un seul fichier YAML nommé `litedocs.yml` à la racine de votre projet.

## Structure de Base

Voici un exemple complet de fichier de configuration :

```yaml
site_name: "Ma Documentation"
site_url: "[https://exemple.com](https://exemple.com)"

# Dossiers
docs_dir: "docs"
site_dir: "site"

# Branding
logo: "assets/logo.png"
favicon: "assets/favicon.ico"

# Thème
theme:
    name: lite

# Imports (Recommandé pour les gros projets)
nav: "config/nav.yml"
plugins: "config/plugins.yml"
```

## Configuration de la Navigation

Vous pouvez séparer votre logique de navigation dans `config/nav.yml`:


```yaml
fr:
    - Accueil: index.md
    - Guide:
        - Installation: guide/installation.md
```
