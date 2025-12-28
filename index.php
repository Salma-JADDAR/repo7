<?php
 
class utilisateur {
    protected int $id_utilisateur;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $createdat;
    protected string $lastLogin;
    
    public function __construct($id_utilisateur, $username, $email, $password, $createdat, $lastLogin){
        $this->id_utilisateur = $id_utilisateur;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->createdat = $createdat;
        $this->lastLogin = $lastLogin;
    }
    
    public function getId(): int {
        return $this->id_utilisateur;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }
    
    public function getPassword(): string {
        return $this->password;
    }
    
    public function getCreatedat(): string {
        return $this->createdat;
    }
    
    public function getLastLogin(): string {
        return $this->lastLogin;
    }
    
    public function setUtilisateur($id): void {  
        $this->id_utilisateur = $id;
    }
    
    public function setUsername($n): void {  
        $this->username = $n;
    }
    
    public function setEmail($e): void {  
        $this->email = $e;
    }
    
    public function setPassword($p): void {  
        $this->password = $p;
    }
    
    public function setCreatedat($c): void {  
        $this->createdat = $c;
    }
    
    public function setLastLogin($l): void { 
        $this->lastLogin = $l;
    }
}

class Moderateur extends utilisateur {
    public function __construct($id, $username, $email, $password, $createdat, $lastLogin) {
        parent::__construct($id, $username, $email, $password, $createdat, $lastLogin);
    }
    
    ///////////////////////////// GESTION DES COMMENTAIRES ///////////////////
    
    // Approuver un commentaire
    public function approuvercommentaire(Collection $collection, $commentId): bool {
        return $collection->approuverCommentaire($commentId);
    }
    
    // Supprimer un commentaire
    public function supprimercommentaire(Collection $collection, $commentId): bool {
        return $collection->supprimerCommentaire($commentId);
    }
    
    ///////////////////////////// GESTION DES ARTICLES ///////////////////
    
    // Publier un article (changer son statut à "publié")
    public function publierarticle(Collection $collection, $articleId): bool {
        $article = $collection->getArticleById($articleId);
        if ($article) {
            $article->setStatus('publié');
            $article->setPublishedAt(date('Y-m-d'));
            $article->setUpdatedAt(date('Y-m-d'));
            return $collection->modifierarticle($article);
        }
        return false;
    }
    
    // Supprimer n'importe quel article
    public function supprimerarticlequelconque(Collection $collection, $articleId): bool {
        return $collection->supprimerArticleMod($articleId);
    }
    
    ///////////////////////////// GESTION DES CATÉGORIES ///////////////////
    
    // Créer une catégorie
    public function creercategorie(Collection $collection, $nom, $description, $parent = null): bool {
        $categorie = $collection->creerCategorie($nom, $description, $parent);
        return ($categorie !== false);
    }
    
    // Supprimer une catégorie
    public function supprimercategorie(Collection $collection, $categorieId): bool {
        return $collection->supprimerCategorie($categorieId);
    }
}

class auteur extends utilisateur {
    private string $bio;
    private array $mesarticles = [];
    
    public function __construct($id, $username, $email, $password, $createdat, $lastLogin, $bio){
        parent::__construct($id, $username, $email, $password, $createdat, $lastLogin);
        $this->bio = $bio;
    }
    
    public function getBio(): string {
        return $this->bio;
    }
    
    public function setBio($b): void { 
        $this->bio = $b;
    }
    
    ///////////////////////////// CRÉER MON ARTICLE /////////////////////////////
    public function creerermonarticle(Article $article): void {
        $this->mesarticles[] = $article;
    }

    ///////////////////////////// MODIFIER MON ARTICLE  /////////////
    public function modifiermonarticle(int $Id, Article $updatedArticle): bool {
        foreach ($this->mesarticles as &$article) {
            if ($article->getId() === $Id) {
                $article = $updatedArticle;
                return true;
            }
        }
        return false;
    }
    
    ///////////////////////////// SUPPRIMER MON ARTICLE  ///////
    public function supprimermonarticle(int $articleId): bool {
        $initialCount = count($this->mesarticles);
        $this->mesarticles = array_filter(
            $this->mesarticles, 
            fn($article) => $article->getId() !== $articleId
        );
    
        return count($this->mesarticles) < $initialCount;
    }
    
    ///////////////////////////// OBTENIR TOUS MES ARTICLES /////////////////
    public function gettousmesarticles(): array {
        return $this->mesarticles;
    }
    
    ///////////////////////////// OBTENIR MON ARTICLE PAR ID /////////////////////
    public function getmonarticleparid(int $articleId): ?Article {
        foreach ($this->mesarticles as $article) {
            if ($article->getId() === $articleId) {
                return $article;
            }
        }
        return null;
    }
    
      ///////////////////////////// COMPTER MES ARTICLES ///////////////////////
    public function comptermesarticles(): int {
        return count($this->mesarticles);
    }
    
}

class Editeur extends Moderateur {
    private string $moderationLevel;
    
    public function __construct($id, $username, $email, $password, $createdat, $lastLogin, $moderationLevel){
        parent::__construct($id, $username, $email, $password, $createdat, $lastLogin);
        $this->moderationLevel = $moderationLevel;
    }
    
    public function getModerationLevel(): string {
        return $this->moderationLevel;
    }
    
    public function setModerationLevel($M): void { 
        $this->moderationLevel = $M;
    }
}

final class Admin extends Moderateur {
    private bool $isSuperAdmin;
    
    public function __construct($id, $username, $email, $password, $createdat, $lastLogin, $isSuperAdmin){
        parent::__construct($id, $username, $email, $password, $createdat, $lastLogin);
        $this->isSuperAdmin = $isSuperAdmin;
    }
    
    public function getIsSuperAdmin(): bool {
        return $this->isSuperAdmin;
    }
    
    public function setIsSuperAdmin($s): void { 
        $this->isSuperAdmin = $s;
    }
    
    ///////////////////////////// GESTION DES UTILISATEURS ///////////////////
    
    // Créer un nouvel utilisateur
    public function creerutilisateur(Collection $collection, $username, $email, $password, $role, $dataSupp = null): bool {
        // Trouver le prochain ID
        $users = $collection->getUsers();
        $maxId = 0;
        foreach ($users as $user) {
            if ($user->getId() > $maxId) {
                $maxId = $user->getId();
            }
        }
        $nouvelId = $maxId + 1;
        
        $date = date('Y-m-d');
        
        // Créer l'utilisateur selon le rôle
        switch ($role) {
            case 'auteur':
                $bio = $dataSupp ?? "Nouvel auteur";
                $nouvelUser = new auteur($nouvelId, $username, $email, $password, $date, $date, $bio);
                break;
            case 'editeur':
                $niveau = $dataSupp ?? "Niveau 1";
                $nouvelUser = new Editeur($nouvelId, $username, $email, $password, $date, $date, $niveau);
                break;
            case 'moderateur':
                $nouvelUser = new Moderateur($nouvelId, $username, $email, $password, $date, $date);
                break;
            case 'admin':
                $isSuper = isset($dataSupp) ? (bool)$dataSupp : false;
                $nouvelUser = new Admin($nouvelId, $username, $email, $password, $date, $date, $isSuper);
                break;
            default:
                return false;
        }
        
        return $collection->ajouterutilisateur($nouvelUser);
    }
    
    // Modifier un utilisateur
    public function modifierutilisateur(utilisateur $user, $newUsername = null, $newEmail = null, $newPassword = null): bool {
        if ($newUsername !== null) {
            $user->setUsername($newUsername);
        }
        
        if ($newEmail !== null) {
            $user->setEmail($newEmail);
        }
        
        if ($newPassword !== null) {
            $user->setPassword($newPassword);
        }
        
        $user->setLastLogin(date('Y-m-d'));
        return true;
    }
    
    // Supprimer un utilisateur
    public function supprimerutilisateur(Collection $collection, $userId): bool {
        return $collection->supprimerutilisateur($userId);
    }
    
    // Changer le rôle d'un utilisateur
    public function changerroleutilisateur(Collection $collection, $userId, $nouveauRole, $dataSupp = null): bool {
        $user = $collection->getUserById($userId);
        
        if (!$user) {
            return false;
        }
        
        // Sauvegarder les données
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $createdat = $user->getCreatedat();
        $lastLogin = $user->getLastLogin();
        
        // Supprimer l'ancien utilisateur
        $collection->supprimerutilisateur($userId);
        
        // Créer le nouvel utilisateur avec le nouveau rôle
        switch ($nouveauRole) {
            case 'auteur':
                $bio = $dataSupp ?? "Bio par défaut";
                $nouvelUser = new auteur($userId, $username, $email, $password, $createdat, $lastLogin, $bio);
                break;
            case 'editeur':
                $niveau = $dataSupp ?? "Niveau 1";
                $nouvelUser = new Editeur($userId, $username, $email, $password, $createdat, $lastLogin, $niveau);
                break;
            case 'moderateur':
                $nouvelUser = new Moderateur($userId, $username, $email, $password, $createdat, $lastLogin);
                break;
            case 'admin':
                $isSuper = isset($dataSupp) ? (bool)$dataSupp : false;
                $nouvelUser = new Admin($userId, $username, $email, $password, $createdat, $lastLogin, $isSuper);
                break;
            default:
                return false;
        }
        
        return $collection->ajouterutilisateur($nouvelUser);
    }
}

class Article {
    private int $id;
    private string $title;
    private string $content;
    private string $excerpt;
    private string $status;         
    private utilisateur $auteur;
    private string $createdAt;
    private ?string $publishedAt;   
    private string $updatedAt;
    private array $categories = [];

    public function __construct(int $id, string $title, string $content, string $excerpt, string $status, utilisateur $auteur, string $createdAt, ?string $publishedAt, string $updatedAt) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->excerpt = $excerpt;
        $this->status = $status;
        $this->auteur = $auteur;
        $this->createdAt = $createdAt;
        $this->publishedAt = $publishedAt;
        $this->updatedAt = $updatedAt;
    }
    
    public function getId(): int {
        return $this->id; 
    }
    
    public function getTitle(): string { 
        return $this->title; 
    }
    
    public function getContent(): string {
        return $this->content; 
    }
    
    public function getExcerpt(): string { 
        return $this->excerpt;
    }
    
    public function getStatus(): string {
        return $this->status; 
    }
    
    public function getAuteur(): utilisateur { 
        return $this->auteur; 
    }
    
    public function getCreatedAt(): string { 
        return $this->createdAt; 
    }
    
    public function getPublishedAt(): ?string {
        return $this->publishedAt;
    }
    
    public function getUpdatedAt(): string { 
        return $this->updatedAt; 
    }

    public function setTitle(string $t): void { 
        $this->title = $t; 
    }
    
    public function setContent(string $c): void {
        $this->content = $c; 
    }
    
    public function setExcerpt(string $e): void {
        $this->excerpt = $e; 
    }
    
    public function setStatus(string $s): void { 
        $this->status = $s; 
    }
    
    public function setPublishedAt(?string $p): void {
        $this->publishedAt = $p;
    }
    
    public function setUpdatedAt(string $u): void { 
        $this->updatedAt = $u; 
    }
    
    // Méthodes simplifiées
    
    // Ajouter une catégorie
    public function addCategorie(Categorie $categorie): void {
        $this->categories[] = $categorie;
    }
    
    // Retirer une catégorie
    public function removeCategorie(Categorie $categorie): void {
        foreach ($this->categories as $key => $cat) {
            if ($cat->getId() === $categorie->getId()) {
                unset($this->categories[$key]);
                break;
            }
        }
    }
    
    // Obtenir les catégories
    public function getCategories(): array {
        return $this->categories;
    }
    
    // Obtenir les commentaires
    public function getComments(Collection $collection): array {
        return $collection->getCommentsByArticle($this->id);
    }
}

class Categorie {
    private int $id;
    private string $name;
    private string $description;
    private ?string $parent; 
    private string $createdAt;

    public function __construct(int $id, string $name, string $description, $createdAt, ?string $parent = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt; 
        $this->parent = $parent;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getParent(): ?string {
        return $this->parent;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }
    
    public function setId($id): void {
        $this->id = $id;
    }
    
    public function setName($n): void {
        $this->name = $n;
    }
    
    public function setDescription($d): void {
        $this->description = $d;
    }
    
    public function setParent($p): void {
        $this->parent = $p;
    }
    
    public function setCreatedAt($c): void {
        $this->createdAt = $c;
    }
    
    // Méthodes simplifiées
    
    // Obtenir le parent (objet)
    public function getParentObject(Collection $collection): ?Categorie {
        if ($this->parent === null) {
            return null;
        }
        
        return $collection->getCategorieByName($this->parent);
    }
    
    // Obtenir l'arbre
    public function getTree(Collection $collection): array {
        $tree = [];
        $categories = $collection->getCategories();
        
        foreach ($categories as $cat) {
            if ($cat->getParent() === $this->name) {
                $tree[] = $cat;
            }
        }
        
        return $tree;
    }
    
    // Obtenir les articles
    public function getArticles(Collection $collection): array {
        $articles = [];
        $allArticles = $collection->getArticles();
        
        foreach ($allArticles as $article) {
            foreach ($article->getCategories() as $cat) {
                if ($cat->getId() === $this->id) {
                    $articles[] = $article;
                    break;
                }
            }
        }
        
        return $articles;
    }
}

class Commentaire {
    private int $id;
    private string $contenu;
    private string $createdAt;
    private bool $approuve;
    private ?int $articleId;
    private ?int $userId;

    public function __construct(int $id, string $contenu, string $createdAt, bool $approuve = true, ?int $articleId = null, ?int $userId = null) {
        $this->id = $id;
        $this->contenu = $contenu;
        $this->createdAt = $createdAt;
        $this->approuve = $approuve;
        $this->articleId = $articleId;
        $this->userId = $userId;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getContenu(): string {
        return $this->contenu;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }
    
    public function getApprouve(): bool {
        return $this->approuve;
    }
    
    public function getArticleId(): ?int {
        return $this->articleId;
    }
    
    public function getUserId(): ?int {
        return $this->userId;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setContenu(string $contenu): void {
        $this->contenu = $contenu;
    }
   
    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }
    
    public function setApprouve(bool $approuve): void {
        $this->approuve = $approuve;
    }
    
    public function setArticleId(?int $articleId): void {
        $this->articleId = $articleId;
    }
    
    public function setUserId(?int $userId): void {
        $this->userId = $userId;
    }
    
    
    public static function addComment(Collection $collection, string $contenu, int $articleId, ?int $userId = null): bool {
        $comment = $collection->ajoutercommentaire($contenu, $articleId, $userId);
        return $comment !== null;
    }
}
?>