# Thèmes

LiteDocs utilise **Twig** comme moteur de template. Vous pouvez facilement personnaliser l'apparence.

## Créer un Thème

1. Créez un dossier dans `themes/` (ex: `themes/mon-theme`).
2. Créez un fichier `base.html.twig` à l'intérieur.
3. Créez un dossier `assets/` pour vos CSS et JS.

## Variables Disponibles

Dans vos templates Twig, vous avez accès à :

* `content` : Le contenu HTML de la page.
* `nav` : L'arbre de navigation.
* `page_title` : Le titre de la page courante.
* `config` : Le tableau complet de configuration.
* `trans` : Les traductions (basées sur la langue actuelle).

## Surcharger les Traductions

Vous pouvez surcharger n'importe quel texte en créant un fichier `translations/fr.yaml` à la racine de votre projet :

```yaml
core:
    ui:
        navigation: "Menu Principal"
```
