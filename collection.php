<?php
require_once __DIR__ . '/index.php';

class Collection {
    private static $instance = null;
    private $storage = [];
    public $current_user = null;
    
    private function __construct() {
    
        $auteur1 = new auteur(1, "salma", "salma@gmail.com", "1234", "2024-01-01", "2025-12-25", "Je suis développeuse passionnée par PHP et les nouvelles technologies.");
        $auteur2 = new auteur(4, "mohamed", "mohamed@gmail.com", "abcd", "2024-03-10", "2025-12-20", "Journaliste tech spécialisé en développement web.");
        
     
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

        $this->storage = [
            'users' => [
                $auteur1,
                new Editeur(2, "sara", "sara@gmail.com", "1348", "2024-02-15", "2025-12-24", "Niveau 2"),
                new Admin(3, "admin", "admin@gmail.com", "123", "2023-12-01", "2025-12-25", true),
                $auteur2
            ],
            'categories' => [
                new Categorie(1, "Technologie", "Articles sur la technologie et l'innovation numérique", "2024-01-01"),
                new Categorie(2, "Programmation", "Articles de programmation et développement logiciel", "2024-01-02", "Technologie"),
                new Categorie(3, "Science", "Articles scientifiques et découvertes récentes", "2024-01-03"),
                new Categorie(4, "Développement Web", "Développement web et mobile, frameworks et bonnes pratiques", "2024-01-04")
            ],
            'articles' => $articles,
            'comments' => [
                new Commentaire(1, "Excellent article pour commencer avec PHP ! Très bien expliqué.", "2024-12-02"),
                new Commentaire(2, "Très bon tutoriel sur la POO, merci pour ces explications claires !", "2024-12-12"),
                new Commentaire(3, "J'attends avec impatience la suite sur les design patterns", "2024-12-16"),
                new Commentaire(4, "Article très complet sur les fonctionnalités avancées de PHP", "2024-12-22")
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
    
    // Méthode pour obtenir les articles publiés
    public function getArticlesPublies() {
        $articlesPublies = [];
        foreach ($this->storage['articles'] as $article) {
            if ($article->getStatus() === 'publié') {
                $articlesPublies[] = $article;
            }
        }
        return $articlesPublies;
    }
    
    // Méthode pour obtenir un article par son ID
    public function getArticleById($id) {
        foreach ($this->storage['articles'] as $article) {
            if ($article->getId() === $id) {
                return $article;
            }
        }
        return null;
    }
    
    // Méthode pour obtenir les articles d'un auteur spécifique
    public function getArticlesByAuthor($authorId) {
        $authorArticles = [];
        foreach ($this->storage['articles'] as $article) {
            if ($article->getAuteur()->getId() === $authorId) {
                $authorArticles[] = $article;
            }
        }
        return $authorArticles;
    }
    
    // Méthode pour obtenir un utilisateur par ID
    public function getUserById($id) {
        foreach ($this->storage['users'] as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        return null;
    }
    
    /////////////////////////////login //////////////////////////////
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
   /////////////////////////////logout///////////////////////////
    public function logout() {
        $this->current_user = null;
    } 
     /////////////////////////////trouverutilisateurcurrent///////////////////////////
    public function getCurrentUser() {
        return $this->current_user;
    }
    ////////////////////////////estceque utilisateur est connecter maintenant///////////////////////////
    public function isLoggedIn() {
        return $this->current_user !== null; 
    }
      ////////////////////////////chkon had utilisateur li hna role /////////////////////
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
   /////////////////////////  ajouter un nouvel article/////////////////////
    
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
    /////////////////////////  modifir  article/////////////////////
   
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
    /////////////////////////  supprime article /////////////
  
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
    echo str_repeat("=", 80) . "\n";
    printf("%-5s %-15s %-25s %-15s %-10s\n", "ID", "Username", "Email", "Password", "Rôle");
    echo str_repeat("-", 80) . "\n";
    
    foreach ($users as $user) {
        $role = "";
        
        if ($user instanceof Admin) $role = "Admin";
        elseif ($user instanceof Editeur) $role = "Editeur";
        elseif ($user instanceof auteur) $role = "Auteur";
        
        printf("%-5d %-15s %-25s %-15s %-10s\n", 
               $user->getId(), 
               $user->getUsername(), 
               $user->getEmail(), 
               str_repeat("*", strlen($user->getPassword())),
               $role);
    }
    echo str_repeat("=", 80) . "\n";
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
    
    // Vérifier si l'article est publié ou si l'utilisateur est connecté
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

function displayComments() {
    $collection = Collection::getInstance();
    $comments = $collection->getComments();
    
    if (empty($comments)) {
        echo "Aucun commentaire pour le moment.\n";
        return;
    }
    
    echo "=== DERNIERS COMMENTAIRES ===\n";
    echo str_repeat("=", 80) . "\n";
    
    foreach ($comments as $comment) {
        echo "[" . $comment->getCreatedAt() . "] " . 
             substr($comment->getContenu(), 0, 60) . 
             (strlen($comment->getContenu()) > 60 ? "..." : "") . "\n";
    }
    echo str_repeat("=", 80) . "\n";
    echo "Total: " . count($comments) . " commentaire(s)\n\n";
}
?>