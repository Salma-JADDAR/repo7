<?php
require_once 'collection.php';

class MenuSystem {
    private $collection;
    
    public function __construct() {
        $this->collection = Collection::getInstance();
    }
    
    public function run() {
        while (true) {
            $this->clearScreen();
            $this->showHeader();
            
            if (!$this->collection->isLoggedIn()) {
                $this->menuVisiteur();
            } else {
                $role = $this->collection->getCurrentUserRole();
                
                if ($role === "Auteur") {
                    $this->menuAuteur();
                } elseif ($role === "Admin") {
                    $this->menuAdmin();
                } elseif ($role === "Moderateur" || $role === "Editeur") {
                    $this->menuModerateur();
                } else {
                    $this->menuUtilisateur();
                }
            }
        }
    }
    
    private function clearScreen() {
        echo "\n\n\n";
    }
    
    private function showHeader() {
        echo "=== BLOG SYSTEM ===\n";
        
        if ($this->collection->isLoggedIn()) {
            $user = $this->collection->getCurrentUser();
            $role = $this->collection->getCurrentUserRole();
            echo "Connecté en tant que: " . $user->getUsername() . " ($role)\n";
        } else {
            echo "Non connecté\n";
        }
        echo "===================\n\n";
    }
    
    private function menuVisiteur() {
        echo "1. Voir catégories\n";
        echo "2. Voir articles publiés\n";
        echo "3. Voir détails article\n";
        echo "4. Ajouter un commentaire\n";
        echo "5. Voir commentaires\n";
        echo "6. Se connecter\n";
        echo "7. Quitter\n";
        
        $choix = $this->demanderChoix(7);
        
        switch ($choix) {
            case 1:
                displayCategories();
                break;
            case 2:
                displayArticles(false);
                break;
            case 3:
                $this->voirDetailsArticle();
                break;
            case 4:
                $this->ajouterCommentaire();
                break;
            case 5:
                displayComments(false);
                break;
            case 6:
                $this->seConnecter();
                break;
            case 7:
                echo "Au revoir!\n";
                exit(0);
        }
        
        $this->attendre();
    }
    
    private function menuUtilisateur() {
        $role = $this->collection->getCurrentUserRole();
        echo "Rôle: $role\n\n";
        
        echo "1. Voir catégories\n";
        echo "2. Voir articles publiés\n";
        echo "3. Voir tous les articles\n";
        echo "4. Voir détails article\n";
        echo "5. Voir mon profil\n";
        echo "6. Ajouter un commentaire\n";
        echo "7. Voir commentaires\n";
        echo "8. Se déconnecter\n";
        echo "9. Quitter\n";
        
        $choix = $this->demanderChoix(9);
        
        switch ($choix) {
            case 1:
                displayCategories();
                break;
            case 2:
                displayArticles(false);
                break;
            case 3:
                displayArticles(true);
                break;
            case 4:
                $this->voirDetailsArticle();
                break;
            case 5:
                $this->voirMonProfil();
                break;
            case 6:
                $this->ajouterCommentaire();
                break;
            case 7:
                displayComments(true);
                break;
            case 8:
                $this->seDeconnecter();
                break;
            case 9:
                echo "Au revoir!\n";
                exit(0);
        }
        
        $this->attendre();
    }
    
    private function menuAuteur() {
        $user = $this->collection->getCurrentUser();
        
        echo "AUTEUR: " . $user->getUsername() . "\n";
        echo "Articles: " . $user->comptermesarticles() . "\n\n";
        
        echo "1. Voir catégories\n";
        echo "2. Voir articles publiés\n";
        echo "3. Voir tous les articles\n";
        echo "4. Voir MES articles\n";
        echo "5. Voir détails article\n";
        echo "6. Voir mon profil\n";
        echo "7. Créer article\n";
        echo "8. Modifier article\n";
        echo "9. Supprimer article\n";
        echo "10. Ajouter un commentaire\n";
        echo "11. Voir commentaires\n";
        echo "12. Se déconnecter\n";
        echo "13. Quitter\n";
        
        $choix = $this->demanderChoix(13);
        
        switch ($choix) {
            case 1:
                displayCategories();
                break;
            case 2:
                displayArticles(false);
                break;
            case 3:
                displayArticles(true);
                break;
            case 4:
                $this->voirMesArticles();
                break;
            case 5:
                $this->voirDetailsArticle();
                break;
            case 6:
                $this->voirMonProfil();
                break;
            case 7:
                $this->creerArticle();
                break;
            case 8:
                $this->modifierArticle();
                break;
            case 9:
                $this->supprimerArticle();
                break;
            case 10:
                $this->ajouterCommentaire();
                break;
            case 11:
                displayComments(true);
                break;
            case 12:
                $this->seDeconnecter();
                break;
            case 13:
                echo "Au revoir!\n";
                exit(0);
        }
        
        $this->attendre();
    }
    
    private function menuModerateur() {
        $user = $this->collection->getCurrentUser();
        $role = $this->collection->getCurrentUserRole();
        
        echo strtoupper($role) . ": " . $user->getUsername() . "\n\n";
        
        echo "=== MENU MODÉRATION ===\n";
        echo "1. Voir catégories\n";
        echo "2. Voir articles publiés\n";
        echo "3. Voir tous les articles\n";
        echo "4. Voir détails article\n";
        echo "5. Voir mon profil\n";
        echo "6. Gérer les commentaires\n";
        echo "7. Gérer les articles\n";
        echo "8. Gérer les catégories\n";
        echo "9. Ajouter un commentaire\n";
        echo "10. Voir commentaires\n";
        echo "11. Se déconnecter\n";
        echo "12. Quitter\n";
        
        $choix = $this->demanderChoix(12);
        
        switch ($choix) {
            case 1:
                displayCategories();
                break;
            case 2:
                displayArticles(false);
                break;
            case 3:
                displayArticles(true);
                break;
            case 4:
                $this->voirDetailsArticle();
                break;
            case 5:
                $this->voirMonProfil();
                break;
            case 6:
                $this->gererCommentaires();
                break;
            case 7:
                $this->gererArticles();
                break;
            case 8:
                $this->gererCategories();
                break;
            case 9:
                $this->ajouterCommentaire();
                break;
            case 10:
                displayComments(true);
                break;
            case 11:
                $this->seDeconnecter();
                break;
            case 12:
                echo "Au revoir!\n";
                exit(0);
        }
        
        $this->attendre();
    }
    
    private function menuAdmin() {
        $user = $this->collection->getCurrentUser();
        
        echo "ADMIN: " . $user->getUsername() . "\n";
        $super = $user->getIsSuperAdmin() ? " (Super Admin)" : "";
        echo "Statut: Administrateur$super\n\n";
        
        echo "=== MENU ADMINISTRATION ===\n";
        echo "1. Voir catégories\n";
        echo "2. Voir articles publiés\n";
        echo "3. Voir tous les articles\n";
        echo "4. Voir détails article\n";
        echo "5. Voir mon profil\n";
        echo "6. Gérer les commentaires\n";
        echo "7. Gérer les articles\n";
        echo "8. Gérer les catégories\n";
        echo "9. Gérer les utilisateurs\n";
        echo "10. Créer un utilisateur\n";
        echo "11. Modifier un utilisateur\n";
        echo "12. Supprimer un utilisateur\n";
        echo "13. Changer rôle utilisateur\n";
        echo "14. Statistiques système\n";
        echo "15. Ajouter un commentaire\n";
        echo "16. Voir commentaires\n";
        echo "17. Se déconnecter\n";
        echo "18. Quitter\n";
        
        $choix = $this->demanderChoix(18);
        
        switch ($choix) {
            case 1:
                displayCategories();
                break;
            case 2:
                displayArticles(false);
                break;
            case 3:
                displayArticles(true);
                break;
            case 4:
                $this->voirDetailsArticle();
                break;
            case 5:
                $this->voirMonProfil();
                break;
            case 6:
                $this->gererCommentaires();
                break;
            case 7:
                $this->gererArticles();
                break;
            case 8:
                $this->gererCategories();
                break;
            case 9:
                $this->gererUtilisateurs();
                break;
            case 10:
                $this->creerUtilisateur();
                break;
            case 11:
                $this->modifierUtilisateur();
                break;
            case 12:
                $this->supprimerUtilisateur();
                break;
            case 13:
                $this->changerRoleUtilisateur();
                break;
            case 14:
                $this->afficherStatistiques();
                break;
            case 15:
                $this->ajouterCommentaire();
                break;
            case 16:
                displayComments(true);
                break;
            case 17:
                $this->seDeconnecter();
                break;
            case 18:
                echo "Au revoir!\n";
                exit(0);
        }
        
        $this->attendre();
    }
    
    private function demanderChoix($max) {
        while (true) {
            echo "\nVotre choix (1-$max): ";
            $input = trim(fgets(STDIN));
            
            if (is_numeric($input)) {
                $choix = (int)$input;
                if ($choix >= 1 && $choix <= $max) {
                    return $choix;
                }
            }
            
            echo "Choix invalide. Réessayez.\n";
        }
    }
    
    private function attendre() {
        echo "\nAppuyez sur Entrée...";
        fgets(STDIN);
    }
    
    private function seConnecter() {
        echo "\n--- CONNEXION ---\n";
        echo "Email: ";
        $email = trim(fgets(STDIN));
        
        echo "Mot de passe: ";
        $password = trim(fgets(STDIN));
        
        if ($this->collection->login($email, $password)) {
            $user = $this->collection->getCurrentUser();
            echo "\nConnecté: " . $user->getUsername() . "\n";
        } else {
            echo "\nErreur de connexion\n";
        }
    }
    
    private function seDeconnecter() {
        $this->collection->logout();
        echo "\nDéconnecté\n";
    }
    
    private function voirMonProfil() {
        $user = $this->collection->getCurrentUser();
        
        echo "\n--- MON PROFIL ---\n";
        echo "ID: " . $user->getId() . "\n";
        echo "Nom: " . $user->getUsername() . "\n";
        echo "Email: " . $user->getEmail() . "\n";
        echo "Inscrit le: " . $user->getCreatedat() . "\n";
        echo "Dernière connexion: " . $user->getLastLogin() . "\n";
        
        if ($user instanceof auteur) {
            echo "Bio: " . $user->getBio() . "\n";
            
            // Compter articles publiés et brouillons
            $articles = $user->gettousmesarticles();
            $publies = 0;
            $brouillons = 0;
            
            foreach ($articles as $article) {
                if ($article->getStatus() === 'publié') {
                    $publies++;
                } else {
                    $brouillons++;
                }
            }
            
            echo "Articles créés: " . count($articles) . "\n";
            echo "  - Publiés: $publies\n";
            echo "  - Brouillons: $brouillons\n";
        }
        
        if ($user instanceof Admin) {
            $super = $user->getIsSuperAdmin() ? "Oui" : "Non";
            echo "Super Admin: $super\n";
        }
        
        if ($user instanceof Editeur) {
            echo "Niveau modération: " . $user->getModerationLevel() . "\n";
        }
    }
    
    private function voirMesArticles() {
        $user = $this->collection->getCurrentUser();
        
        if ($user instanceof auteur) {
            $articles = $user->gettousmesarticles();
            
            echo "\n--- MES ARTICLES ---\n";
            
            if (empty($articles)) {
                echo "Aucun article\n";
            } else {
                $publies = 0;
                $brouillons = 0;
                
                echo str_repeat("-", 90) . "\n";
                printf("%-5s %-40s %-15s %-15s\n", "ID", "Titre", "Statut", "Date création");
                echo str_repeat("-", 90) . "\n";
                
                foreach ($articles as $article) {
                    $statut = $article->getStatus();
                    if ($statut === 'publié') {
                        $publies++;
                    } else {
                        $brouillons++;
                    }
                    
                    printf("%-5d %-40s %-15s %-15s\n", 
                           $article->getId(), 
                           substr($article->getTitle(), 0, 38) . (strlen($article->getTitle()) > 38 ? "..." : ""),
                           $statut,
                           $article->getCreatedAt());
                }
                echo str_repeat("-", 90) . "\n";
                echo "Total: " . count($articles) . " article(s)\n";
                echo "  - Publiés: $publies\n";
                echo "  - Brouillons: $brouillons\n";
            }
        } else {
            echo "Vous n'êtes pas auteur\n";
        }
    }
    
    private function voirDetailsArticle() {
        echo "\nID de l'article: ";
        $id = (int)trim(fgets(STDIN));
        
        displayArticleDetail($id);
    }
    
    // ==================== COMMENTAIRES ====================
    
    private function ajouterCommentaire() {
        echo "\n--- AJOUTER UN COMMENTAIRE ---\n";
        
        echo "ID de l'article: ";
        $articleId = (int)trim(fgets(STDIN));
        
        $article = $this->collection->getArticleById($articleId);
        if (!$article) {
            echo "Article non trouvé\n";
            return;
        }
        
        if ($article->getStatus() !== 'publié' && !$this->collection->isLoggedIn()) {
            echo "Cet article n'est pas publié\n";
            return;
        }
        
        echo "Votre commentaire: ";
        $contenu = trim(fgets(STDIN));
        
        if (empty($contenu)) {
            echo "Le commentaire ne peut pas être vide\n";
            return;
        }
        
        $userId = null;
        if ($this->collection->isLoggedIn()) {
            $userId = $this->collection->getCurrentUser()->getId();
        }
        
        $commentaire = $this->collection->ajoutercommentaire($contenu, $articleId, $userId);
        
        if ($commentaire) {
            if ($commentaire->getApprouve()) {
                echo "Commentaire ajouté avec succès\n";
            } else {
                echo "Commentaire ajouté, en attente d'approbation par un modérateur\n";
            }
        } else {
            echo "Erreur lors de l'ajout du commentaire\n";
        }
    }
    
    private function gererCommentaires() {
        $user = $this->collection->getCurrentUser();
        
        if (!($user instanceof Moderateur || $user instanceof Admin || $user instanceof Editeur)) {
            echo "Réservé aux modérateurs\n";
            return;
        }
        
        echo "\n--- GESTION DES COMMENTAIRES ---\n";
        echo "1. Voir commentaires en attente\n";
        echo "2. Approuver un commentaire\n";
        echo "3. Supprimer un commentaire\n";
        echo "4. Voir tous les commentaires\n";
        echo "5. Retour\n";
        
        $choix = $this->demanderChoix(5);
        
        switch ($choix) {
            case 1:
                $this->voirCommentairesEnAttente();
                break;
            case 2:
                $this->approuverCommentaire();
                break;
            case 3:
                $this->supprimerCommentaire();
                break;
            case 4:
                displayComments(true);
                break;
            case 5:
                return;
        }
    }
    
    private function voirCommentairesEnAttente() {
        displayCommentsEnAttente();
    }
    
    private function approuverCommentaire() {
        $user = $this->collection->getCurrentUser();
        
        if (!($user instanceof Moderateur || $user instanceof Admin || $user instanceof Editeur)) {
            echo "Réservé aux modérateurs\n";
            return;
        }
        
        echo "\n--- APPROUVER UN COMMENTAIRE ---\n";
        displayCommentsEnAttente();
        
        echo "\nID du commentaire à approuver: ";
        $commentId = (int)trim(fgets(STDIN));
        
        if ($user->approuvercommentaire($this->collection, $commentId)) {
            echo "Commentaire approuvé avec succès\n";
        } else {
            echo "Erreur lors de l'approbation\n";
        }
    }
    
    private function supprimerCommentaire() {
        $user = $this->collection->getCurrentUser();
        
        if (!($user instanceof Moderateur || $user instanceof Admin || $user instanceof Editeur)) {
            echo "Réservé aux modérateurs\n";
            return;
        }
        
        echo "\n--- SUPPRIMER UN COMMENTAIRE ---\n";
        displayComments(true);
        
        echo "\nID du commentaire à supprimer: ";
        $commentId = (int)trim(fgets(STDIN));
        
        echo "Confirmer la suppression (oui/non): ";
        $confirmer = trim(fgets(STDIN));
        
        if (strtolower($confirmer) === 'oui') {
            if ($user->supprimercommentaire($this->collection, $commentId)) {
                echo "Commentaire supprimé avec succès\n";
            } else {
                echo "Erreur lors de la suppression\n";
            }
        } else {
            echo "Annulé\n";
        }
    }
    
    // ==================== GESTION DES ARTICLES (MODÉRATEURS) ====================
    
    private function gererArticles() {
        $user = $this->collection->getCurrentUser();
        
        if (!($user instanceof Moderateur || $user instanceof Admin || $user instanceof Editeur)) {
            echo "Réservé aux modérateurs\n";
            return;
        }
        
        echo "\n--- GESTION DES ARTICLES ---\n";
        echo "1. Publier un article\n";
        echo "2. Supprimer n'importe quel article\n";
        echo "3. Voir tous les articles\n";
        echo "4. Retour\n";
        
        $choix = $this->demanderChoix(4);
        
        switch ($choix) {
            case 1:
                $this->publierArticle();
                break;
            case 2:
                $this->supprimerArticleQuelconque();
                break;
            case 3:
                displayArticles(true);
                break;
            case 4:
                return;
        }
    }
    
    private function publierArticle() {
        $user = $this->collection->getCurrentUser();
        
        if (!($user instanceof Moderateur || $user instanceof Admin || $user instanceof Editeur)) {
            echo "Réservé aux modérateurs\n";
            return;
        }
        
        echo "\n--- PUBLIER UN ARTICLE ---\n";
        displayArticles(true);
        
        echo "\nID de l'article à publier: ";
        $articleId = (int)trim(fgets(STDIN));
        
        if ($user->publierarticle($this->collection, $articleId)) {
            echo "Article publié avec succès\n";
        } else {
            echo "Erreur lors de la publication\n";
        }
    }
    
    private function supprimerArticleQuelconque() {
        $user = $this->collection->getCurrentUser();
        
        if (!($user instanceof Moderateur || $user instanceof Admin || $user instanceof Editeur)) {
            echo "Réservé aux modérateurs\n";
            return;
        }
        
        echo "\n--- SUPPRIMER N'IMPORTE QUEL ARTICLE ---\n";
        displayArticles(true);
        
        echo "\nID de l'article à supprimer: ";
        $articleId = (int)trim(fgets(STDIN));
        
        $article = $this->collection->getArticleById($articleId);
        if (!$article) {
            echo "Article non trouvé\n";
            return;
        }
        
        echo "\nATTENTION: Suppression de: " . $article->getTitle() . "\n";
        echo "Auteur: " . $article->getAuteur()->getUsername() . "\n";
        echo "Confirmer (oui/non): ";
        $confirmer = trim(fgets(STDIN));
        
        if (strtolower($confirmer) === 'oui') {
            if ($user->supprimerarticlequelconque($this->collection, $articleId)) {
                echo "Article supprimé avec succès\n";
            } else {
                echo "Erreur lors de la suppression\n";
            }
        } else {
            echo "Annulé\n";
        }
    }
    
    // ==================== GESTION DES CATÉGORIES ====================
    
    private function gererCategories() {
        $user = $this->collection->getCurrentUser();
        
        if (!($user instanceof Moderateur || $user instanceof Admin || $user instanceof Editeur)) {
            echo "Réservé aux modérateurs\n";
            return;
        }
        
        echo "\n--- GESTION DES CATÉGORIES ---\n";
        echo "1. Créer une catégorie\n";
        echo "2. Supprimer une catégorie\n";
        echo "3. Voir les catégories\n";
        echo "4. Retour\n";
        
        $choix = $this->demanderChoix(4);
        
        switch ($choix) {
            case 1:
                $this->creerCategorie();
                break;
            case 2:
                $this->supprimerCategorie();
                break;
            case 3:
                displayCategories();
                break;
            case 4:
                return;
        }
    }
    
    private function creerCategorie() {
    $user = $this->collection->getCurrentUser();
    
    if (!($user instanceof Moderateur || $user instanceof Admin || $user instanceof Editeur)) {
        echo "Réservé aux modérateurs\n";
        return;
    }
    
    echo "\n--- CRÉER UNE CATÉGORIE ---\n";
    
    echo "Nom de la catégorie: ";
    $nom = trim(fgets(STDIN));
    
    if (empty($nom)) {
        echo "Le nom ne peut pas être vide\n";
        return;
    }
    
    echo "Description: ";
    $description = trim(fgets(STDIN));
    
    echo "Catégorie parent (vide si aucune): ";
    $parent = trim(fgets(STDIN));
    if (empty($parent)) $parent = null;
    
    $result = $user->creercategorie($this->collection, $nom, $description, $parent);
    
    if ($result) {
        echo "Catégorie créée avec succès\n";
    } else {
        echo "Erreur lors de la création (catégorie déjà existante ou parent invalide)\n";
    }
}
    
    private function supprimerCategorie() {
        $user = $this->collection->getCurrentUser();
        
        if (!($user instanceof Moderateur || $user instanceof Admin || $user instanceof Editeur)) {
            echo "Réservé aux modérateurs\n";
            return;
        }
        
        echo "\n--- SUPPRIMER UNE CATÉGORIE ---\n";
        displayCategories();
        
        echo "\nID de la catégorie à supprimer: ";
        $categorieId = (int)trim(fgets(STDIN));
        
        echo "Confirmer (oui/non): ";
        $confirmer = trim(fgets(STDIN));
        
        if (strtolower($confirmer) === 'oui') {
            if ($user->supprimercategorie($this->collection, $categorieId)) {
                echo "Catégorie supprimée avec succès\n";
            } else {
                echo "Erreur lors de la suppression (la catégorie a peut-être des sous-catégories)\n";
            }
        } else {
            echo "Annulé\n";
        }
    }
    
    // ==================== GESTION DES UTILISATEURS (ADMIN) ====================
    
    private function gererUtilisateurs() {
        echo "\n--- GESTION DES UTILISATEURS ---\n";
        displayUsers();
    }
    
    private function creerUtilisateur() {
        $user = $this->collection->getCurrentUser();
        
        if (!$user instanceof Admin) {
            echo "Réservé aux administrateurs\n";
            return;
        }
        
        echo "\n--- CRÉER UN UTILISATEUR ---\n";
        
        echo "Nom d'utilisateur: ";
        $username = trim(fgets(STDIN));
        
        echo "Email: ";
        $email = trim(fgets(STDIN));
        
        echo "Mot de passe: ";
        $password = trim(fgets(STDIN));
        
        echo "Rôle (auteur/editeur/moderateur/admin): ";
        $role = trim(fgets(STDIN));
        
        $dataSupp = null;
        if ($role === 'auteur') {
            echo "Bio: ";
            $dataSupp = trim(fgets(STDIN));
        } elseif ($role === 'editeur') {
            echo "Niveau de modération (Niveau 1/Niveau 2/Niveau 3): ";
            $dataSupp = trim(fgets(STDIN));
        } elseif ($role === 'admin') {
            echo "Super Admin (oui/non): ";
            $super = trim(fgets(STDIN));
            $dataSupp = ($super === 'oui');
        }
        
        if ($user->creerutilisateur($this->collection, $username, $email, $password, $role, $dataSupp)) {
            echo "Utilisateur créé avec succès\n";
        } else {
            echo "Erreur lors de la création\n";
        }
    }
    
    private function modifierUtilisateur() {
        $user = $this->collection->getCurrentUser();
        
        if (!$user instanceof Admin) {
            echo "Réservé aux administrateurs\n";
            return;
        }
        
        echo "\n--- MODIFIER UN UTILISATEUR ---\n";
        displayUsers();
        
        echo "\nID de l'utilisateur à modifier: ";
        $id = (int)trim(fgets(STDIN));
        
        $userAModifier = $this->collection->getUserById($id);
        
        if (!$userAModifier) {
            echo "Utilisateur non trouvé\n";
            return;
        }
        
        // Ne pas permettre de modifier soi-même
        if ($userAModifier->getId() === $user->getId()) {
            echo "Vous ne pouvez pas vous modifier vous-même\n";
            return;
        }
        
        echo "\nUtilisateur: " . $userAModifier->getUsername() . "\n";
        echo "Email actuel: " . $userAModifier->getEmail() . "\n";
        
        echo "\nNouveau nom d'utilisateur (vide=pas de changement): ";
        $newUsername = trim(fgets(STDIN));
        if (empty($newUsername)) $newUsername = null;
        
        echo "Nouvel email (vide=pas de changement): ";
        $newEmail = trim(fgets(STDIN));
        if (empty($newEmail)) $newEmail = null;
        
        echo "Nouveau mot de passe (vide=pas de changement): ";
        $newPassword = trim(fgets(STDIN));
        if (empty($newPassword)) $newPassword = null;
        
        if ($user->modifierutilisateur($userAModifier, $newUsername, $newEmail, $newPassword)) {
            // Mettre à jour dans la collection
            if ($this->collection->mettreajourutilisateur($userAModifier)) {
                echo "Utilisateur modifié avec succès\n";
            } else {
                echo "Erreur lors de la mise à jour\n";
            }
        } else {
            echo "Erreur lors de la modification\n";
        }
    }
    
    private function supprimerUtilisateur() {
        $user = $this->collection->getCurrentUser();
        
        if (!$user instanceof Admin) {
            echo "Réservé aux administrateurs\n";
            return;
        }
        
        echo "\n--- SUPPRIMER UN UTILISATEUR ---\n";
        displayUsers();
        
        echo "\nID de l'utilisateur à supprimer: ";
        $id = (int)trim(fgets(STDIN));
        
        // Ne pas permettre de se supprimer soi-même
        if ($id === $user->getId()) {
            echo "Vous ne pouvez pas vous supprimer vous-même\n";
            return;
        }
        
        $userASupprimer = $this->collection->getUserById($id);
        
        if (!$userASupprimer) {
            echo "Utilisateur non trouvé\n";
            return;
        }
        
        echo "\nATTENTION: Suppression de: " . $userASupprimer->getUsername() . "\n";
        echo "Email: " . $userASupprimer->getEmail() . "\n";
        
        if ($userASupprimer instanceof auteur) {
            $articles = $userASupprimer->comptermesarticles();
            echo "Cet utilisateur est un auteur avec $articles article(s)\n";
            echo "Tous ses articles seront également supprimés\n";
        }
        
        echo "\nConfirmer la suppression (oui/non): ";
        $confirmer = trim(fgets(STDIN));
        
        if (strtolower($confirmer) === 'oui') {
            if ($user->supprimerutilisateur($this->collection, $id)) {
                echo "Utilisateur supprimé avec succès\n";
            } else {
                echo "Erreur lors de la suppression\n";
            }
        } else {
            echo "Annulé\n";
        }
    }
    
    private function changerRoleUtilisateur() {
        $user = $this->collection->getCurrentUser();
        
        if (!$user instanceof Admin) {
            echo "Réservé aux administrateurs\n";
            return;
        }
        
        echo "\n--- CHANGER LE RÔLE D'UN UTILISATEUR ---\n";
        displayUsers();
        
        echo "\nID de l'utilisateur: ";
        $id = (int)trim(fgets(STDIN));
        
        // Ne pas permettre de changer son propre rôle
        if ($id === $user->getId()) {
            echo "Vous ne pouvez pas changer votre propre rôle\n";
            return;
        }
        
        $userAChanger = $this->collection->getUserById($id);
        
        if (!$userAChanger) {
            echo "Utilisateur non trouvé\n";
            return;
        }
        
        echo "\nUtilisateur: " . $userAChanger->getUsername() . "\n";
        
        // Déterminer le rôle actuel
        $roleActuel = "";
        if ($userAChanger instanceof Admin) $roleActuel = "admin";
        elseif ($userAChanger instanceof Editeur) $roleActuel = "editeur";
        elseif ($userAChanger instanceof Moderateur) $roleActuel = "moderateur";
        elseif ($userAChanger instanceof auteur) $roleActuel = "auteur";
        
        echo "Rôle actuel: " . $roleActuel . "\n";
        
        echo "\nNouveau rôle (auteur/editeur/moderateur/admin): ";
        $nouveauRole = trim(fgets(STDIN));
        
        if ($nouveauRole === $roleActuel) {
            echo "L'utilisateur a déjà ce rôle\n";
            return;
        }
        
        $dataSupp = null;
        if ($nouveauRole === 'auteur') {
            echo "Bio: ";
            $dataSupp = trim(fgets(STDIN));
        } elseif ($nouveauRole === 'editeur') {
            echo "Niveau de modération (Niveau 1/Niveau 2/Niveau 3): ";
            $dataSupp = trim(fgets(STDIN));
        } elseif ($nouveauRole === 'admin') {
            echo "Super Admin (oui/non): ";
            $super = trim(fgets(STDIN));
            $dataSupp = ($super === 'oui');
        }
        
        echo "\nATTENTION: Changement de rôle pour: " . $userAChanger->getUsername() . "\n";
        echo "De: $roleActuel vers: $nouveauRole\n";
        
        if ($userAChanger instanceof auteur && $nouveauRole !== 'auteur') {
            $articles = $userAChanger->comptermesarticles();
            if ($articles > 0) {
                echo "Cet auteur a $articles article(s). Que faire de ses articles?\n";
                echo "1. Supprimer tous ses articles\n";
                echo "2. Conserver les articles (ils resteront attribués à cet utilisateur)\n";
                echo "Choix: ";
                $choixArticles = (int)trim(fgets(STDIN));
                
                if ($choixArticles === 1) {
                    echo "Ses articles seront supprimés\n";
                }
            }
        }
        
        echo "\nConfirmer le changement de rôle (oui/non): ";
        $confirmer = trim(fgets(STDIN));
        
        if (strtolower($confirmer) === 'oui') {
            if ($user->changerroleutilisateur($this->collection, $id, $nouveauRole, $dataSupp)) {
                echo "Rôle changé avec succès\n";
            } else {
                echo "Erreur lors du changement de rôle\n";
            }
        } else {
            echo "Annulé\n";
        }
    }
    
    private function afficherStatistiques() {
        echo "\n--- STATISTIQUES DU SYSTÈME ---\n";
        echo str_repeat("=", 60) . "\n";
        
        $users = $this->collection->getUsers();
        $articles = $this->collection->getArticles();
        $categories = $this->collection->getCategories();
        $comments = $this->collection->getComments();
        $commentsApprouves = $this->collection->getCommentsApprouves();
        $commentsEnAttente = $this->collection->getCommentsEnAttente();
        
        // Compter les rôles
        $roles = ['Admin' => 0, 'Editeur' => 0, 'Moderateur' => 0, 'Auteur' => 0];
        foreach ($users as $user) {
            if ($user instanceof Admin) $roles['Admin']++;
            elseif ($user instanceof Editeur) $roles['Editeur']++;
            elseif ($user instanceof Moderateur) $roles['Moderateur']++;
            elseif ($user instanceof auteur) $roles['Auteur']++;
        }
        
        // Compter les articles par statut
        $statusCount = ['publié' => 0, 'brouillon' => 0];
        foreach ($articles as $article) {
            $status = $article->getStatus();
            if (isset($statusCount[$status])) {
                $statusCount[$status]++;
            } else {
                $statusCount[$status] = 1;
            }
        }
        
        // Compter les articles par auteur
        $articlesParAuteur = [];
        foreach ($users as $user) {
            if ($user instanceof auteur) {
                $count = $user->comptermesarticles();
                if ($count > 0) {
                    $articlesParAuteur[$user->getUsername()] = $count;
                }
            }
        }
        
        echo "UTILISATEURS: " . count($users) . "\n";
        foreach ($roles as $role => $count) {
            if ($count > 0) {
                echo "  - $role: $count\n";
            }
        }
        
        echo "\nARTICLES: " . count($articles) . "\n";
        foreach ($statusCount as $status => $count) {
            echo "  - $status: $count\n";
        }
        
        if (!empty($articlesParAuteur)) {
            echo "\nARTICLES PAR AUTEUR:\n";
            arsort($articlesParAuteur);
            foreach ($articlesParAuteur as $auteur => $count) {
                echo "  - $auteur: $count article(s)\n";
            }
        }
        
        echo "\nCATÉGORIES: " . count($categories) . "\n";
        echo "COMMENTAIRES: " . count($comments) . "\n";
        echo "  - Approuvés: " . count($commentsApprouves) . "\n";
        echo "  - En attente: " . count($commentsEnAttente) . "\n";
        
        echo str_repeat("=", 60) . "\n";
    }
    
    // ==================== ARTICLES (AUTEUR) ====================
    
    private function creerArticle() {
        $user = $this->collection->getCurrentUser();
        
        if (!$user instanceof auteur) {
            echo "Réservé aux auteurs\n";
            return;
        }
        
        echo "\n--- NOUVEL ARTICLE ---\n";
        
        echo "Titre: ";
        $titre = trim(fgets(STDIN));
        
        echo "Contenu: ";
        $contenu = trim(fgets(STDIN));
        
        echo "Extrait: ";
        $extrait = trim(fgets(STDIN));
        
        echo "Statut (publié/brouillon): ";
        $statut = trim(fgets(STDIN));
        
        // Trouver ID
        $articles = $this->collection->getArticles();
        $maxId = 0;
        foreach ($articles as $article) {
            if ($article->getId() > $maxId) {
                $maxId = $article->getId();
            }
        }
        $nouvelId = $maxId + 1;
        
        $date = date('Y-m-d');
        $publieLe = ($statut === 'publié') ? $date : null;
        
        $article = new Article(
            $nouvelId,
            $titre,
            $contenu,
            $extrait,
            $statut,
            $user,
            $date,
            $publieLe,
            $date
        );
        
        if ($this->collection->ajouterarticle($article)) {
            echo "Article créé (ID: $nouvelId)\n";
            echo "Statut: " . $statut . "\n";
        } else {
            echo "Erreur\n";
        }
    }
    
    private function modifierArticle() {
        $user = $this->collection->getCurrentUser();
        
        if (!$user instanceof auteur) {
            echo "Réservé aux auteurs\n";
            return;
        }
        
        $mesArticles = $user->gettousmesarticles();
        
        if (empty($mesArticles)) {
            echo "Aucun article à modifier\n";
            return;
        }
        
        echo "\n--- MODIFIER ARTICLE ---\n";
        echo "Vos articles:\n";
        foreach ($mesArticles as $article) {
            $statut = $article->getStatus();
            echo $article->getId() . ". " . substr($article->getTitle(), 0, 30) . "... [$statut]\n";
        }
        
        echo "\nID à modifier: ";
        $id = (int)trim(fgets(STDIN));
        
        $article = null;
        foreach ($mesArticles as $art) {
            if ($art->getId() === $id) {
                $article = $art;
                break;
            }
        }
        
        if (!$article) {
            echo "Article non trouvé\n";
            return;
        }
        
        echo "\nArticle: " . $article->getTitle() . "\n";
        echo "Statut actuel: " . $article->getStatus() . "\n";
        
        echo "\nNouveau titre (vide=pas de changement): ";
        $nouveauTitre = trim(fgets(STDIN));
        if (!empty($nouveauTitre)) {
            $article->setTitle($nouveauTitre);
        }
        
        echo "Nouveau statut (publié/brouillon, vide=pas de changement): ";
        $nouveauStatut = trim(fgets(STDIN));
        if (!empty($nouveauStatut)) {
            $article->setStatus($nouveauStatut);
            if ($nouveauStatut === 'publié' && !$article->getPublishedAt()) {
                $article->setPublishedAt(date('Y-m-d'));
            }
        }
        
        $article->setUpdatedAt(date('Y-m-d'));
        
        if ($this->collection->modifierarticle($article)) {
            echo "Article modifié\n";
        } else {
            echo "Erreur\n";
        }
    }
    
    private function supprimerArticle() {
        $user = $this->collection->getCurrentUser();
        
        if (!$user instanceof auteur) {
            echo "Réservé aux auteurs\n";
            return;
        }
        
        $mesArticles = $user->gettousmesarticles();
        
        if (empty($mesArticles)) {
            echo "Aucun article\n";
            return;
        }
        
        echo "\n--- SUPPRIMER ARTICLE ---\n";
        echo "Vos articles:\n";
        foreach ($mesArticles as $article) {
            echo $article->getId() . ". " . $article->getTitle() . " [" . $article->getStatus() . "]\n";
        }
        
        echo "\nID à supprimer: ";
        $id = (int)trim(fgets(STDIN));
        
        $article = null;
        foreach ($mesArticles as $art) {
            if ($art->getId() === $id) {
                $article = $art;
                break;
            }
        }
        
        if (!$article) {
            echo "Article non trouvé\n";
            return;
        }
        
        echo "\nATTENTION: Suppression de: " . $article->getTitle() . "\n";
        echo "Statut: " . $article->getStatus() . "\n";
        echo "Confirmer (oui/non): ";
        $confirmer = trim(fgets(STDIN));
        
        if (strtolower($confirmer) === 'oui') {
            if ($this->collection->supprimerarticle($id)) {
                echo "Article supprimé\n";
            } else {
                echo "Erreur\n";
            }
        } else {
            echo "Annulé\n";
        }
    }
}

// Lancer
$menu = new MenuSystem();
$menu->run();