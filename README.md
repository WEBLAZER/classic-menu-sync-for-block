# Menu Sync for Navigation Block

Un plugin WordPress qui synchronise automatiquement les blocs Navigation avec les menus classiques en utilisant le système d'import natif de WordPress.

## Description

Menu Sync for Navigation Block permet de combler le fossé entre les menus WordPress classiques et le nouveau système de blocs Navigation. Ce plugin vous permet de :

* **Synchroniser les blocs Navigation avec les menus classiques** - Gardez vos blocs Navigation à jour avec les modifications de vos menus classiques
* **Fonctionnalité de synchronisation automatique** - Mettez à jour automatiquement les blocs Navigation lorsque les menus classiques sont modifiés
* **Option de synchronisation manuelle** - Synchronisez à la demande quand nécessaire
* **Intégration WordPress native** - Utilise le convertisseur de menu intégré de WordPress pour une compatibilité transparente
* **Intégration Block Editor** - Contrôles faciles à utiliser directement dans la barre latérale du bloc Navigation

Parfait pour les sites en transition des thèmes classiques vers les thèmes basés sur les blocs, ou pour les développeurs qui souhaitent maintenir à la fois les systèmes de navigation classiques et basés sur les blocs.

## Caractéristiques principales

* **Zéro configuration** - Fonctionne immédiatement
* **Optimisé pour les performances** - Utilise les fonctions natives de WordPress
* **Convivial pour les développeurs** - Code propre et bien documenté
* **Prêt pour la traduction** - Support complet de l'internationalisation
* **Sécurisé** - Suit les meilleures pratiques de sécurité WordPress

## Installation

1. Téléchargez le plugin ou clonez ce dépôt dans `/wp-content/plugins/menu-sync-for-navigation-block`
2. Activez le plugin via l'écran 'Plugins' dans WordPress
3. Éditez n'importe quel bloc Navigation dans le Block Editor
4. Dans la barre latérale du bloc Navigation, trouvez le panneau "Auto Sync with Classic Menu"
5. Sélectionnez un menu classique à synchroniser et configurez vos préférences de synchronisation

## Exigences

* WordPress 6.0 ou supérieur
* PHP 7.4 ou supérieur
* Thème compatible avec les blocs Navigation (WordPress 5.9+)

## Utilisation

### Synchronisation automatique

1. Éditez un bloc Navigation dans le Block Editor
2. Dans la barre latérale, trouvez le panneau "Menu Synchronization"
3. Sélectionnez un menu classique dans le menu déroulant
4. Activez la synchronisation automatique si vous souhaitez que le bloc se mette à jour automatiquement lorsque le menu classique est modifié
5. Cliquez sur "Sync Now" pour une synchronisation immédiate

### Synchronisation manuelle

Vous pouvez déclencher une synchronisation manuelle à tout moment en cliquant sur le bouton "Sync Now" dans le panneau de synchronisation.

## API REST

Le plugin expose des endpoints REST API pour un accès programmatique :

* `GET/POST /wp-json/menu-sync-for-navigation-block/v1/settings/{post_id}` - Gérer les paramètres de synchronisation
* `POST /wp-json/menu-sync-for-navigation-block/v1/sync/{post_id}/{menu_id}` - Déclencher une opération de synchronisation

## Développement

Ce plugin utilise la classe native `WP_Classic_To_Block_Menu_Converter` de WordPress pour assurer une compatibilité maximale et une pérennité.

### Structure du code

```
menu-sync-for-navigation-block/
├── assets/
│   ├── editor.js      # Scripts pour l'éditeur de blocs
│   └── editor.css     # Styles pour l'éditeur de blocs
├── languages/         # Fichiers de traduction
├── menu-sync-for-navigation-block.php  # Fichier principal
├── uninstall.php      # Script de désinstallation
└── readme.txt         # Fichier readme pour WordPress.org
```

## FAQ

**Est-ce que cela fonctionne avec tous les thèmes ?**

Oui, ce plugin fonctionne avec tout thème qui supporte le bloc Navigation (WordPress 5.9+).

**Est-ce que cela affectera mes menus existants ?**

Non, ce plugin lit uniquement les menus classiques et met à jour les blocs Navigation. Vos menus classiques restent inchangés.

**Puis-je synchroniser plusieurs blocs Navigation avec le même menu ?**

Oui, vous pouvez lier plusieurs blocs Navigation au même menu classique.

**Que se passe-t-il si je supprime un menu classique ?**

Le bloc Navigation conservera son dernier contenu synchronisé. Vous pouvez le mettre à jour manuellement ou le lier à un menu différent.

## Support

Pour le support, les demandes de fonctionnalités ou les rapports de bugs, visitez : https://weblazer.fr

## Licence

GPL v2 ou ultérieure - https://www.gnu.org/licenses/gpl-2.0.html

## Auteur

**weblazer35** - [weblazer.fr](https://weblazer.fr)

## Changelog

### 1.0.0
* Version initiale
* Synchronisation des blocs Navigation avec les menus classiques
* Fonctionnalité de synchronisation automatique
* Option de synchronisation manuelle
* Intégration Block Editor
* Endpoints REST API pour les opérations de synchronisation

