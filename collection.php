<?php
require_once __DIR__ . '/index.php';

class Collection {
    private static $instance = null;
    private $storage = [];
    public $current_user = null;
    
    private function __construct() {
    
        $auteur1 = new auteur(1, "salma", "salma@gmail.com", "1234", "2024-01-01", "2025-12-25", "Je suis développeuse passionnée par PHP et les nouvelles technologies.");
        $auteur2 = new auteur(4, "mohamed", "mohamed@gmail.com", "abcd", "2024-03-10", "2025-12-20", "Journaliste tech spécialisé en développement web.");
        
        $moderateur = new Moderateur(5, "moderateur", "mod@mail.com", "modpass", "2024-01-10", "2025-12-20");
     
        $articles = [
            new Article(1, "Introduction à PHP", 
                "PHP est un langage de script côté serveur très utilisé pour le développement web. Il permet de créer des sites web dynamiques et interactifs. PHP s'intègre facilement avec HTML et supporte plusieurs bases de données.", 
                "Découvrez PHP pour le web", 
                "publié", $auteur1, "2024-12-01", "2024-12-02", "2024-12-02"),
                
            new Article(2, "POO en PHP", 
                "La programmation orientée objet en PHP permet de structurer son code de manière modulaire et réutilisable grâce aux classes, objets, héritage et polymorphisme. La POO améliore la maintenabilité du code.", 
                "Comprendre les classes et objets", 
                "publié", $auteur1, "2024-12-10", "2024-12-11", "2024-12-11"),
                
            new Article(3, "Design Patterns en PHP", 
                "Les design patterns en développement sont des solutions réutilisables aux problèmes courants de conception logicielle. Ils aident à créer un code plus maintenable, extensible et testable.", 
                "Patterns de conception courants", 
                "brouillon", $auteur2, "2024-12-15", null, "2024-12-15"),
                
            new Article(4, "PHP Avancé : Fonctionnalités Modernes", 
                "Les fonctionnalités avancées de PHP comme les traits, les générateurs, les espaces de noms et les closures permettent d'écrire du code plus efficace et professionnel. Découvrez ces features essentielles.", 
                "Aller plus loin avec PHP", 
                "publié", $auteur2, "2024-12-20", "2024-12-21", "2024-12-21")
        ];
        
       
        foreach ($articles as $article) {
            $auteur = $article->getAuteur();
            if ($auteur instanceof auteur) {
                $auteur->creerermonarticle($article);
            }
        }
        
        // Créer des catégories
        $technologie = new Categorie(1, "Technologie", "Articles sur la technologie et l'innovation numérique", "2024-01-01");
        $programmation = new Categorie(2, "Programmation", "Articles de programmation et développement logiciel", "2024-01-02", "Technologie");
        $science = new Categorie(3, "Science", "Articles scientifiques et découvertes récentes", "2024-01-03");
        $webdev = new Categorie(4, "Développement Web", "Développement web et mobile, frameworks et bonnes pratiques", "2024-01-04", "Programmation");
        
        // Associer des catégories aux articles
        $articles[0]->addCategorie($technologie);
        $articles[0]->addCategorie($programmation);
        $articles[1]->addCategorie($programmation);
        $articles[1]->addCategorie($webdev);
        $articles[2]->addCategorie($programmation);
        $articles[3]->addCategorie($webdev);

        $this->storage = [
            'users' => [
                $auteur1,
                new Editeur(2, "sara", "sara@gmail.com", "1348", "2024-02-15", "2025-12-24", "Niveau 2"),
                new Admin(3, "admin", "admin@gmail.com", "123", "2023-12-01", "2025-12-25", true),
                $auteur2,
                $moderateur
            ],
            'categories' => [
                $technologie,
                $programmation,
                $science,
                $webdev
            ],
            'articles' => $articles,
            'comments' => [
                new Commentaire(1, "Excellent article pour commencer avec PHP ! Très bien expliqué.", "2024-12-02", true, 1, null),
                new Commentaire(2, "Très bon tutoriel sur la POO, merci pour ces explications claires !", "2024-12-12", true, 2, null),
                new Commentaire(3, "J'attends avec impatience la suite sur les design patterns", "2024-12-16", false, 3, 4),
                new Commentaire(4, "Article très complet sur les fonctionnalités avancées de PHP", "2024-12-22", true, 4, 1)
            ]
        ];
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getStorage() {
        return $this->storage;
    }

    public function getUsers() {
        return $this->storage['users'];
    }

    public function getCategories() {
        return $this->storage['categories'];
    }

    public function getArticles() {
        return $this->storage['articles'];
    }

    public function getComments() {
        return $this->storage['comments'] ?? [];
    }
    
    public function getCommentsApprouves() {
        $commentsApprouves = [];
        foreach ($this->storage['comments'] as $comment) {
            if ($comment->getApprouve()) {
                $commentsApprouves[] = $comment;
            }
        }
        return $commentsApprouves;
    }
    
    public function getCommentsEnAttente() {
        $commentsEnAttente = [];
        foreach ($this->storage['comments'] as $comment) {
            if (!$comment->getApprouve()) {
                $commentsEnAttente[] = $comment;
            }
        }
        return $commentsEnAttente;
    }
    
    public function getCommentsByArticle($articleId) {
        $commentsArticle = [];
        foreach ($this->storage['comments'] as $comment) {
            if ($comment->getArticleId() === $articleId) {
                $commentsArticle[] = $comment;
            }
        }
        return $commentsArticle;
    }
    
    public function getArticlesPublies() {
        $articlesPublies = [];
        foreach ($this->storage['articles'] as $article) {
            if ($article->getStatus() === 'publié') {
                $articlesPublies[] = $article;
            }
        }
        return $articlesPublies;
    }
    
    public function getArticleById($id) {
        foreach ($this->storage['articles'] as $article) {
            if ($article->getId() === $id) {
                return $article;
            }
        }
        return null;
    }
    
    public function getArticlesByAuthor($authorId) {
        $authorArticles = [];
        foreach ($this->storage['articles'] as $article) {
            if ($article->getAuteur()->getId() === $authorId) {
                $authorArticles[] = $article;
            }
        }
        return $authorArticles;
    }
    
    public function getUserById($id) {
        foreach ($this->storage['users'] as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        return null;
    }
    
    public function getUserByEmail($email) {
        foreach ($this->storage['users'] as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        return null;
    }
    
    // Méthodes simples pour catégories
    
    public function getCategorieById($id) {
        foreach ($this->storage['categories'] as $categorie) {
            if ($categorie->getId() === $id) {
                return $categorie;
            }
        }
        return null;
    }
    
    public function getCategorieByName($name) {
        foreach ($this->storage['categories'] as $categorie) {
            if ($categorie->getName() === $name) {
                return $categorie;
            }
        }
        return null;
    }
    
    // Associer catégorie à article
    public function associerCategorieAArticle($articleId, $categorieId) {
        $article = $this->getArticleById($articleId);
        $categorie = $this->getCategorieById($categorieId);
        
        if ($article && $categorie) {
            $article->addCategorie($categorie);
            return true;
        }
        return false;
    }
    
    // Dissocier catégorie d'article
    public function dissocierCategorieDeArticle($articleId, $categorieId) {
        $article = $this->getArticleById($articleId);
        $categorie = $this->getCategorieById($categorieId);
        
        if ($article && $categorie) {
            $article->removeCategorie($categorie);
            return true;
        }
        return false;
    }
    
    // Obtenir catégories d'article
    public function getCategoriesDeArticle($articleId) {
        $article = $this->getArticleById($articleId);
        return $article ? $article->getCategories() : [];
    }
    
    // Obtenir articles d'une catégorie
    public function getArticlesDeCategorie($categorieId) {
        $categorie = $this->getCategorieById($categorieId);
        return $categorie ? $categorie->getArticles($this) : [];
    }
    
    // Afficher arbre des catégories
    public function displayArbreCategories() {
        $categories = $this->getCategories();
        
        echo "=== ARBRE DES CATÉGORIES ===\n";
        
        foreach ($categories as $cat) {
            if ($cat->getParent() === null) {
                $this->displayCategorie($cat, 0);
            }
        }
    }
    
    private function displayCategorie($categorie, $niveau) {
        $prefix = str_repeat('  ', $niveau);
        echo $prefix . "- " . $categorie->getName() . " (ID: " . $categorie->getId() . ")\n";
        
        foreach ($this->getCategories() as $cat) {
            if ($cat->getParent() === $categorie->getName()) {
                $this->displayCategorie($cat, $niveau + 2);
            }
        }
    }
    
    // Gestion des utilisateurs
    
    public function ajouterutilisateur(utilisateur $user) {
        $this->storage['users'][] = $user;
        return true;
    }
    
    public function supprimerutilisateur($userId) {
        foreach ($this->storage['users'] as $key => $user) {
            if ($user->getId() === $userId) {
                if ($this->current_user && $this->current_user->getId() === $userId) {
                    return false;
                }
                
                if ($user instanceof auteur) {
                    $articlesASupprimer = [];
                    foreach ($this->storage['articles'] as $articleKey => $article) {
                        if ($article->getAuteur()->getId() === $userId) {
                            $articlesASupprimer[] = $articleKey;
                        }
                    }
                    
                    foreach ($articlesASupprimer as $articleKey) {
                        unset($this->storage['articles'][$articleKey]);
                    }
                    $this->storage['articles'] = array_values($this->storage['articles']);
                }
                
                unset($this->storage['users'][$key]);
                $this->storage['users'] = array_values($this->storage['users']);
                return true;
            }
        }
        return false;
    }
    
    public function mettreajourutilisateur(utilisateur $updatedUser) {
        foreach ($this->storage['users'] as $key => $user) {
            if ($user->getId() === $updatedUser->getId()) {
                $this->storage['users'][$key] = $updatedUser;
                return true;
            }
        }
        return false;
    }
    
    // Gestion des commentaires
    
    public function ajoutercommentaire($contenu, $articleId, $userId = null) {
        $comments = $this->storage['comments'];
        $maxId = 0;
        foreach ($comments as $comment) {
            if ($comment->getId() > $maxId) {
                $maxId = $comment->getId();
            }
        }
        $nouvelId = $maxId + 1;
        
        $userCommentId = null;
        if ($userId) {
            $userCommentId = $userId;
        } elseif ($this->current_user) {
            $userCommentId = $this->current_user->getId();
        }
        
        $approuve = ($this->current_user !== null);
        
        $commentaire = new Commentaire(
            $nouvelId,
            $contenu,
            date('Y-m-d'),
            $approuve,
            $articleId,
            $userCommentId
        );
        
        $this->storage['comments'][] = $commentaire;
        return $commentaire;
    }
    
    public function approuverCommentaire($commentId) {
        foreach ($this->storage['comments'] as $comment) {
            if ($comment->getId() === $commentId) {
                $comment->setApprouve(true);
                return true;
            }
        }
        return false;
    }
    
    public function supprimerCommentaire($commentId) {
        foreach ($this->storage['comments'] as $key => $comment) {
            if ($comment->getId() === $commentId) {
                unset($this->storage['comments'][$key]);
                $this->storage['comments'] = array_values($this->storage['comments']);
                return true;
            }
        }
        return false;
    }
    
    // Gestion des catégories
    
    public function creerCategorie($nom, $description, $parent = null) {
        foreach ($this->storage['categories'] as $categorie) {
            if ($categorie->getName() === $nom) {
                return false;
            }
        }
        
        if ($parent !== null) {
            $parentExiste = false;
            foreach ($this->storage['categories'] as $categorie) {
                if ($categorie->getName() === $parent) {
                    $parentExiste = true;
                    break;
                }
            }
            if (!$parentExiste) {
                return false;
            }
        }
        
        $categories = $this->storage['categories'];
        $maxId = 0;
        foreach ($categories as $categorie) {
            if ($categorie->getId() > $maxId) {
                $maxId = $categorie->getId();
            }
        }
        $nouvelId = $maxId + 1;
        
        $categorie = new Categorie(
            $nouvelId,
            $nom,
            $description,
            date('Y-m-d'),
            $parent
        );
        
        $this->storage['categories'][] = $categorie;
        return $categorie;
    }
    
    public function supprimerCategorie($categorieId) {
        foreach ($this->storage['categories'] as $key => $categorie) {
            if ($categorie->getId() === $categorieId) {
                $hasChildren = false;
                foreach ($this->storage['categories'] as $c) {
                    if ($c->getParent() === $categorie->getName()) {
                        $hasChildren = true;
                        break;
                    }
                }
                
                if ($hasChildren) {
                    return false;
                }
                
                unset($this->storage['categories'][$key]);
                $this->storage['categories'] = array_values($this->storage['categories']);
                return true;
            }
        }
        return false;
    }
    
    // Gestion des articles (modérateurs)
    
    public function supprimerArticleMod($articleId) {
        foreach ($this->storage['articles'] as $key => $article) {
            if ($article->getId() === $articleId) {
                $author = $article->getAuteur();
                if ($author instanceof auteur) {
                    $author->supprimermonarticle($articleId);
                }
                
                unset($this->storage['articles'][$key]);
                $this->storage['articles'] = array_values($this->storage['articles']);
                
                $this->supprimerCommentairesArticle($articleId);
                
                return true;
            }
        }
        return false;
    }
    
    private function supprimerCommentairesArticle($articleId) {
        $commentairesASupprimer = [];
        foreach ($this->storage['comments'] as $key => $comment) {
            if ($comment->getArticleId() === $articleId) {
                $commentairesASupprimer[] = $key;
            }
        }
        
        foreach ($commentairesASupprimer as $key) {
            unset($this->storage['comments'][$key]);
        }
        
        if (!empty($commentairesASupprimer)) {
            $this->storage['comments'] = array_values($this->storage['comments']);
        }
    }
    
    // Login/logout
    
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return false;
        }
        
        foreach($this->storage['users'] as $u) {
            if ($u->getEmail() === $email && $u->getPassword() === $password) {
                $this->current_user = $u;
                return true;
            }
        }
        return false;
    }
   
    public function logout() {
        $this->current_user = null;
    } 
     
    public function getCurrentUser() {
        return $this->current_user;
    }
    
    public function isLoggedIn() {
        return $this->current_user !== null; 
    }
      
    public function getCurrentUserRole() {
        if (!$this->current_user) {
            return null;
        }
        
        if ($this->current_user instanceof Admin) return "Admin";
        if ($this->current_user instanceof Editeur) return "Editeur";
        if ($this->current_user instanceof Moderateur) return "Moderateur";
        if ($this->current_user instanceof auteur) return "Auteur";
        
        return "Utilisateur";
    }
   
   // Gestion des articles
   
    public function ajouterarticle(Article $article) {
        if (!$this->current_user instanceof auteur) {
            return false;
        }
        $this->storage['articles'][] = $article;
        
        $author = $article->getAuteur();
        if ($author instanceof auteur) {
            $author->creerermonarticle($article);
        }
        
        return true;
    }
    
    public function modifierarticle(Article $updatedArticle) {
        $currentUser = $this->current_user;
        $articleAuthor = $updatedArticle->getAuteur();
        
        if (!$currentUser instanceof auteur || $currentUser->getId() !== $articleAuthor->getId()) {
            return false;
        }
        
        foreach ($this->storage['articles'] as $key => $article) {
            if ($article->getId() === $updatedArticle->getId()) {
                $this->storage['articles'][$key] = $updatedArticle;
                
                $author = $updatedArticle->getAuteur();
                if ($author instanceof auteur) {
                    $author->modifiermonarticle($updatedArticle->getId(), $updatedArticle);
                }
                
                return true;
            }
        }
        return false;
    }
    
    public function supprimerarticle($articleId) {
        $article = $this->getArticleById($articleId);
        if (!$article) {
            return false;
        }
        
        $currentUser = $this->current_user;
        $articleAuthor = $article->getAuteur();
        
        if (!$currentUser instanceof auteur || $currentUser->getId() !== $articleAuthor->getId()) {
            return false;
        }
        
        foreach ($this->storage['articles'] as $key => $article) {
            if ($article->getId() === $articleId) {
                unset($this->storage['articles'][$key]);
                $this->storage['articles'] = array_values($this->storage['articles']);
                
                $author = $article->getAuteur();
                if ($author instanceof auteur) {
                    $author->supprimermonarticle($articleId);
                }
                
                return true;
            }
        }
        return false;
    }
}

function displayUsers() {
    $collection = Collection::getInstance();
    $users = $collection->getUsers();
    
    echo "=== LISTE DES UTILISATEURS ===\n";
    echo str_repeat("=", 100) . "\n";
    printf("%-5s %-15s %-25s %-15s %-10s %-15s\n", "ID", "Username", "Email", "Password", "Rôle", "Inscrit le");
    echo str_repeat("-", 100) . "\n";
    
    foreach ($users as $user) {
        $role = "";
        
        if ($user instanceof Admin) $role = "Admin";
        elseif ($user instanceof Editeur) $role = "Editeur";
        elseif ($user instanceof Moderateur) $role = "Moderateur";
        elseif ($user instanceof auteur) $role = "Auteur";
        
        printf("%-5d %-15s %-25s %-15s %-10s %-15s\n", 
               $user->getId(), 
               $user->getUsername(), 
               $user->getEmail(), 
               str_repeat("*", strlen($user->getPassword())),
               $role,
               $user->getCreatedat());
    }
    echo str_repeat("=", 100) . "\n";
    echo "Total: " . count($users) . " utilisateur(s)\n\n";
}

function displayCategories() {
    $collection = Collection::getInstance();
    $categories = $collection->getCategories();
    
    echo "=== LISTE DES CATÉGORIES ===\n";
    echo str_repeat("=", 70) . "\n";
    printf("%-5s %-20s %-30s %-15s\n", "ID", "Nom", "Description", "Parent");
    echo str_repeat("-", 70) . "\n";
    
    foreach ($categories as $categorie) {
        printf("%-5d %-20s %-30s %-15s\n", 
               $categorie->getId(), 
               $categorie->getName(), 
               substr($categorie->getDescription(), 0, 28) . "...",
               $categorie->getParent() ?: "Aucun");
    }
    echo str_repeat("=", 70) . "\n";
    echo "Total: " . count($categories) . " catégorie(s)\n\n";
}

function displayArticles($showAll = false) {
    $collection = Collection::getInstance();
    
    if ($showAll && $collection->isLoggedIn()) {
        $articles = $collection->getArticles();
        $title = "TOUS LES ARTICLES";
    } else {
        $articles = $collection->getArticlesPublies();
        $title = "ARTICLES PUBLIÉS";
    }
    
    echo "=== " . $title . " ===\n";
    echo str_repeat("=", 120) . "\n";
    printf("%-5s %-30s %-20s %-15s %-15s %-20s\n", 
           "ID", "Titre", "Auteur", "Statut", "Publication", "Extrait");
    echo str_repeat("-", 120) . "\n";
    
    if (empty($articles)) {
        echo "Aucun article à afficher.\n";
    } else {
        foreach ($articles as $article) {
            printf("%-5d %-30s %-20s %-15s %-15s %-20s\n", 
                   $article->getId(), 
                   substr($article->getTitle(), 0, 28) . (strlen($article->getTitle()) > 28 ? "..." : ""),
                   substr($article->getAuteur()->getUsername(), 0, 18) . (strlen($article->getAuteur()->getUsername()) > 18 ? "..." : ""),
                   $article->getStatus(),
                   $article->getPublishedAt() ?: "Non publié",
                   substr($article->getExcerpt(), 0, 18) . (strlen($article->getExcerpt()) > 18 ? "..." : ""));
        }
    }
    echo str_repeat("=", 120) . "\n";
    echo "Total: " . count($articles) . " article(s)\n\n";
}

function displayArticleDetail($articleId) {
    $collection = Collection::getInstance();
    $article = $collection->getArticleById($articleId);
    
    if (!$article) {
        echo "\nArticle non trouvé !\n\n";
        return;
    }
    
    if ($article->getStatus() !== 'publié' && !$collection->isLoggedIn()) {
        echo "\n Cet article n'est pas disponible publiquement.\n";
        echo "Connectez-vous pour y accéder.\n\n";
        return;
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "DÉTAIL DE L'ARTICLE\n";
    echo str_repeat("=", 50) . "\n";
    echo "ID: " . $article->getId() . "\n";
    echo "Titre: " . $article->getTitle() . "\n";
    echo "Auteur: " . $article->getAuteur()->getUsername() . "\n";
    echo "Email auteur: " . $article->getAuteur()->getEmail() . "\n";
    echo "Statut: " . $article->getStatus() . "\n";
    echo "Date création: " . $article->getCreatedAt() . "\n";
    echo "Date publication: " . ($article->getPublishedAt() ?: "Non publié") . "\n";
    echo "Dernière modification: " . $article->getUpdatedAt() . "\n";
    echo str_repeat("-", 50) . "\n";
    echo "Extrait: " . $article->getExcerpt() . "\n";
    echo str_repeat("-", 50) . "\n";
    echo "Contenu: " . $article->getContent() . "\n";
    
    // Catégories
    $categories = $article->getCategories();
    if (!empty($categories)) {
        echo str_repeat("-", 50) . "\n";
        echo "CATÉGORIES:\n";
        foreach ($categories as $cat) {
            echo "  - " . $cat->getName() . "\n";
        }
    }
    
    // Commentaires
    $comments = $collection->getCommentsByArticle($articleId);
    if (!empty($comments)) {
        echo str_repeat("-", 50) . "\n";
        echo "COMMENTAIRES (" . count($comments) . "):\n";
        foreach ($comments as $comment) {
            $approuve = $comment->getApprouve() ? "✓" : "✗";
            echo "  [$approuve] " . $comment->getContenu() . " [" . $comment->getCreatedAt() . "]\n";
        }
    }
    
    echo str_repeat("=", 50) . "\n\n";
}

function displayAuthorArticles($authorId) {
    $collection = Collection::getInstance();
    $authorArticles = $collection->getArticlesByAuthor($authorId);
    
    $author = $collection->getUserById($authorId);
    $authorName = $author ? $author->getUsername() : "Inconnu";
    
    echo "=== ARTICLES DE " . strtoupper($authorName) . " (ID: $authorId) ===\n";
    echo str_repeat("=", 100) . "\n";
    printf("%-5s %-30s %-15s %-15s\n", 
           "ID", "Titre", "Statut", "Publication");
    echo str_repeat("-", 100) . "\n";
    
    if (empty($authorArticles)) {
        echo "Cet auteur n'a encore publié aucun article.\n";
    } else {
        foreach ($authorArticles as $article) {
            printf("%-5d %-30s %-15s %-15s\n", 
                   $article->getId(), 
                   substr($article->getTitle(), 0, 28) . (strlen($article->getTitle()) > 28 ? "..." : ""),
                   $article->getStatus(),
                   $article->getPublishedAt() ?: "Non publié");
        }
    }
    echo str_repeat("=", 100) . "\n";
    echo "Total: " . count($authorArticles) . " article(s)\n\n";
}

function displayComments($showAll = false) {
    $collection = Collection::getInstance();
    
    if ($showAll && $collection->isLoggedIn()) {
        $comments = $collection->getComments();
        $title = "TOUS LES COMMENTAIRES";
    } else {
        $comments = $collection->getCommentsApprouves();
        $title = "COMMENTAIRES";
    }
    
    echo "=== " . $title . " ===\n";
    echo str_repeat("=", 80) . "\n";
    
    if (empty($comments)) {
        echo "Aucun commentaire pour le moment.\n";
    } else {
        foreach ($comments as $comment) {
            $approuve = $comment->getApprouve() ? "✓" : "✗";
            echo "[$approuve][" . $comment->getCreatedAt() . "] " . 
                 substr($comment->getContenu(), 0, 60) . 
                 (strlen($comment->getContenu()) > 60 ? "..." : "") . "\n";
        }
    }
    echo str_repeat("=", 80) . "\n";
    echo "Total: " . count($comments) . " commentaire(s)\n\n";
}

function displayCommentsEnAttente() {
    $collection = Collection::getInstance();
    $comments = $collection->getCommentsEnAttente();
    
    echo "=== COMMENTAIRES EN ATTENTE D'APPROBATION ===\n";
    echo str_repeat("=", 80) . "\n";
    
    if (empty($comments)) {
        echo "Aucun commentaire en attente.\n";
    } else {
        foreach ($comments as $comment) {
            echo "ID: " . $comment->getId() . " - " . 
                 substr($comment->getContenu(), 0, 50) . 
                 (strlen($comment->getContenu()) > 50 ? "..." : "") . 
                 " [" . $comment->getCreatedAt() . "]\n";
        }
    }
    echo str_repeat("=", 80) . "\n";
    echo "Total: " . count($comments) . " commentaire(s) en attente\n\n";
}

// Fonction pour afficher l'arbre des catégories
function displayArbreCategories() {
    $collection = Collection::getInstance();
    $collection->displayArbreCategories();
}
?>