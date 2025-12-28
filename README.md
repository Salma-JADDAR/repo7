Système de Gestion de Blog en PHP
📋 Description du Projet
Ce projet est un système de gestion de blog développé en PHP orienté objet. Il permet de gérer des utilisateurs, articles, catégories et commentaires avec différents niveaux de permissions.

🏗️ Architecture du Projet
Structure des fichiers :
text
├── index.php          # Classes principales
├── collection.php     # Gestionnaire de données (Singleton)
├── test.php          # Interface en ligne de commande
└── README.md         # Documentation
👥 Classes Principales
1. utilisateur (Classe de base)
id_utilisateur, username, email, password

createdat, lastLogin

Getters et setters pour tous les attributs

2. auteur (Hérite de utilisateur)
Attributs supplémentaires : bio, mesarticles[]

Méthodes spécifiques :

creerermonarticle() : Créer un article

modifiermonarticle() : Modifier un article

supprimermonarticle() : Supprimer un article

gettousmesarticles() : Obtenir tous mes articles

comptermesarticles() : Compter mes articles

3. Moderateur (Hérite de utilisateur)
Gestion des commentaires :

approuvercommentaire()

supprimercommentaire()

Gestion des articles :

publierarticle()

supprimerarticlequelconque()

Gestion des catégories :

creercategorie()

supprimercategorie()

4. Editeur (Hérite de Moderateur)
Attribut supplémentaire : moderationLevel

5. Admin (Hérite de Moderateur) - classe finale
Attribut supplémentaire : isSuperAdmin

Gestion des utilisateurs :

creerutilisateur()

modifierutilisateur()

supprimerutilisateur()

changerroleutilisateur()

6. Article
Attributs : id, title, content, excerpt, status, auteur

createdAt, publishedAt, updatedAt, categories[]

Méthodes de gestion des catégories :

addCategorie() : Ajouter une catégorie

removeCategorie() : Retirer une catégorie

getCategories() : Obtenir les catégories

getComments() : Obtenir les commentaires

7. Categorie
Attributs : id, name, description, parent, createdAt

Méthodes :

getParentObject() : Obtenir l'objet parent

getTree() : Obtenir l'arbre des sous-catégories

getArticles() : Obtenir les articles de la catégorie

8. Commentaire
Attributs : id, contenu, createdAt, approuve, articleId, userId

Méthode statique :

addComment() : Ajouter un commentaire

🗃️ Collection (Singleton)
La classe Collection est un gestionnaire de données qui utilise le pattern Singleton.

Fonctionnalités principales :
Gestion des utilisateurs : ajout, suppression, modification

Gestion des articles : filtrage par statut, auteur

Gestion des commentaires : approuvés/en attente

Gestion des catégories : création, suppression, arbre hiérarchique

Système d'authentification : login/logout

Gestion des associations : articles-catégories

Méthodes importantes :
php
// Gestion des catégories
associerCategorieAArticle($articleId, $categorieId)
dissocierCategorieDeArticle($articleId, $categorieId)
getCategoriesDeArticle($articleId)
getArticlesDeCategorie($categorieId)

// Gestion des utilisateurs
login($email, $password)
logout()
getCurrentUser()
isLoggedIn()
getCurrentUserRole()

// Affichage
displayArbreCategories()
🎮 Interface en Ligne de Commande
Le fichier test.php contient un système de menu interactif avec différents rôles :

Rôles disponibles :
Visiteur (Non connecté) :

Voir articles publiés

Voir catégories

Ajouter des commentaires

Se connecter

Auteur :

Toutes les fonctionnalités visiteur

Créer/modifier/supprimer ses articles

Voir ses statistiques

Modérateur/Éditeur :

Toutes les fonctionnalités auteur

Gérer les commentaires (approuver/supprimer)

Gérer les articles (publier/supprimer)

Gérer les catégories

Administrateur :

Toutes les fonctionnalités modérateur

Gérer les utilisateurs (CRUD)

Changer les rôles

Voir les statistiques système

🚀 Fonctionnalités Spécifiques
Gestion des Catégories
Hiérarchie parent-enfant

Association avec les articles

Affichage en arbre

Système de Commentaires
Commentaires approuvés automatiquement pour utilisateurs connectés

Modération nécessaire pour visiteurs

Filtrage par article

Gestion des Articles
Statuts : "publié" ou "brouillon"

Dates de création/publication/mise à jour

Association avec les auteurs

Sécurité
Mot de passe en clair (à améliorer pour production)

Vérification des permissions par rôle

Empêchement de suppression de soi-même

💾 Données Initiales
Utilisateurs prédéfinis :
salma - Auteur (ID: 1)

sara - Éditeur (ID: 2)

admin - Administrateur (ID: 3)

mohamed - Auteur (ID: 4)

moderateur - Modérateur (ID: 5)

Catégories :
Technologie

Programmation (enfant de Technologie)

Science

Développement Web (enfant de Programmation)

Articles :
4 articles prédéfinis avec différents statuts et auteurs

🛠️ Installation et Utilisation
Prérequis :
PHP 7.4 ou supérieur

Accès en ligne de commande

Lancement :
bash
php test.php
Authentification :
Utiliser les identifiants des utilisateurs prédéfinis :

Email : salma@gmail.com / mot de passe : 1234 (Auteur)

Email : admin@gmail.com / mot de passe : 123 (Admin)

Email : mod@mail.com / mot de passe : modpass (Modérateur)

📊 Méthodes d'Affichage
Le fichier collection.php inclut des fonctions d'affichage :

php
displayUsers()           // Liste tous les utilisateurs
displayCategories()      // Liste les catégories
displayArticles($showAll)// Liste les articles (tous ou publiés seulement)
displayArticleDetail($id)// Détails d'un article avec commentaires
displayComments($showAll)// Liste des commentaires
displayArbreCategories() // Arbre hiérarchique des catégories
🔧 Améliorations Possibles
Sécurité :

Hachage des mots de passe

Protection contre les injections

Base de données :

Migration vers MySQL/PostgreSQL

Persistance des données

Interface :

Interface web (HTML/CSS)

API REST

Fonctionnalités :

Recherche d'articles

Tags

Images

Export PDF

📝 Notes Techniques
Utilisation du pattern Singleton pour Collection

Héritage pour les rôles utilisateurs

Tableaux associatifs pour le stockage en mémoire

Gestion simple des IDs auto-incrémentés

🧪 Tests
Le système peut être testé avec les commandes suivantes dans le menu :

Connexion avec différents rôles

Création d'articles (en tant qu'auteur)

Modération de commentaires (modérateur/admin)

Gestion des utilisateurs (admin seulement)

📄 Licence
Projet éducatif - Libre d'utilisation et modification