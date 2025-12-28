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
        echo "4. Se connecter\n";
        echo "5. Quitter\n";
        
        $choix = $this->demanderChoix(5);
        
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
                $this->seConnecter();
                break;
            case 5:
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
        
        if ($role === "Admin" || $role === "Editeur") {
            echo "6. Voir utilisateurs\n";
        }
        
        echo "7. Se déconnecter\n";
        echo "8. Quitter\n";
        
        $maxChoix = ($role === "Admin" || $role === "Editeur") ? 8 : 8;
        $choix = $this->demanderChoix($maxChoix);
        
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
                if ($role === "Admin" || $role === "Editeur") {
                    displayUsers();
                } else {
                    $this->seDeconnecter();
                }
                break;
            case 7:
                $this->seDeconnecter();
                break;
            case 8:
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
        echo "10. Se déconnecter\n";
        echo "11. Quitter\n";
        
        $choix = $this->demanderChoix(11);
        
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
                $this->seDeconnecter();
                break;
            case 11:
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