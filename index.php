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
}

class Commentaire {
    private int $id;
    private string $contenu;
    private string $createdAt;

    public function __construct(int $id, string $contenu, string $createdAt) {
        $this->id = $id;
        $this->contenu = $contenu;
        $this->createdAt = $createdAt;
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

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setContenu(string $contenu): void {
        $this->contenu = $contenu;
    }
   
    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }
}
?>