# Bienvenue sur LiteDocs

**LiteDocs** est un générateur de site statique moderne et léger écrit en **PHP**. Il est conçu pour être rapide, extensible et facile à utiliser.

## Pourquoi LiteDocs ?

Nous avons créé LiteDocs car nous avions besoin d'un outil :
* **Simple :** Aucune configuration complexe requise.
* **Rapide :** Écrit en PHP 8.4+ pour la performance.
* **Extensible :** Système de plugins valide utilisant Symfony EventDispatcher.

## Exemple Rapide

Voici comment initialiser le kernel en PHP :

```php
use LiteDocs\Core\Kernel;

$kernel = new Kernel(__DIR__);
$kernel->boot();
$kernel->build();

<div data-search-ignore style="background: #e0f7fa; color: #006064; padding: 15px; border-radius: 4px; margin-top: 20px;"> <strong>Astuce :</strong> Ce bloc utilise l'attribut <code>data-search-ignore</code>. Il sera visible pour l'utilisateur mais ignoré par le moteur de recherche. </div>
```
