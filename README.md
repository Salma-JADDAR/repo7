# BLOGCMS CONSOLE EDITION



---

## 🎯 Aperçu

**BlogCMS Console Edition** est un système de gestion de contenu pour blog fonctionnant en ligne de commande, développé en PHP avec une approche orientée objet pure. Conçu pour l'agence "CodeCrafters Digital", ce projet répond aux besoins de MediaPress International pour gérer leur blog corporate sans interface web.

### Caractéristiques principales
- ✅ Gestion complète des articles, catégories et utilisateurs
- ✅ Système de rôles et permissions avancé
- ✅ Interface console intuitive avec menus contextuels
- ✅ Persistance des données en JSON
- ✅ Respect strict des principes POO

### Technologies
- **Langage** : PHP 7.4+
- **Stockage** : JSON files
- **Architecture** : Pure POO, sans frameworks
- **Interface** : Console/CLI

---

## 🚀 Fonctionnalités

### 👥 Gestion des utilisateurs
- **4 rôles** : Visiteur, Auteur, Éditeur, Administrateur
- **Permissions granulaires** selon la matrice des permissions
- **Hachage sécurisé** des mots de passe
- **Gestion des sessions** en console

### 📝 Gestion des articles
- Cycle de vie : Brouillon → Publié → Archivé
- Assignation multiple aux catégories
- Recherche et filtrage avancés
- Dates de création/publication automatiques

### 📂 Gestion des catégories
- Arborescence hiérarchique illimitée
- Prévention des boucles parentales
- Nom unique par niveau hiérarchique
- Affichage en arbre avec statistiques

### 🛡️ Sécurité
- Authentification par login/mot de passe
- Vérification des permissions par rôle
- Protection contre les auto-suppressions
- Validation des données d'entrée

---

## 📦 Installation

### Prérequis
- PHP 7.4 ou supérieur
- Extensions PHP : `json`, `mbstring`
- Terminal/Console supportant les couleurs (optionnel)

### Installation rapide
```bash
# 1. Télécharger le projet
git clone [url-du-projet]
cd blogcms-console

# 2. Configurer les permissions
chmod +x scripts/*.php

# 3. Initialiser la base de données
php scripts/init.php

# 4. Créer un administrateur
php scripts/create-admin.php

# 5. Lancer l'application
php index.php