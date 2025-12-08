# Installation

## Prérequis

Avant d'utiliser LiteDocs, assurez-vous que votre environnement respecte ces critères :

* **PHP :** 8.4 ou supérieur
* **Composer :** installé globalement (optionnel si PHAR)
* **Extensions :** `php-xml`, `php-mbstring`

## Option 1 : Via PHAR (Recommandé)

Vous pouvez télécharger l'exécutable autonome directement. C'est la méthode la plus simple.

```bash
# Télécharger la dernière version
wget https://github.com/ezar101/litedocs/releases/latest/download/litedocs.phar

# Rendre exécutable
chmod +x litedocs.phar

# Déplacer dans bin (optionnel)
sudo mv litedocs.phar /usr/local/bin/litedocs
```
## Option 2 : Via Composer

Vous pouvez aussi l'installer comme dépendance globale :

```bash
composer global require ezar101/litedocs
```

## Flux de Développement

Lorsque vous rédigez de la documentation, vous ne voulez pas lancer la commande de build manuellement à chaque fois.

Utilisez la commande **watch** pour surveiller les modifications dans vos dossiers `docs` et `themes` :

```bash
litedocs watch
```

## Prévisualisation en direct

Comme LiteDocs est un générateur de site statique, il n'inclut pas de serveur web. Pour prévisualiser votre site localement :

1. Ouvrez un terminal et lancez le watcher : `litedocs watch`
2. Ouvrez un second terminal et lancez le serveur interne de PHP :

    ```bash
    php -S localhost:8000 -t site
    ```
3. Allez sur `http://localhost:8000` dans votre navigateur.
