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
wget [https://github.com/ezar101/litedocs/releases/latest/download/litedocs.phar](https://github.com/ezar101/litedocs/releases/latest/download/litedocs.phar)

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
