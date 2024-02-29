<?php

class Article {
    private $id;
    private $title;
    private $content;
    private $author;
    private $date;

   
    public function __construct($id, $title, $content, $author, $date) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->date = $date;
        
    }

    public function save($conn) {
        
        if ($this->id) {
            $sql = "UPDATE posts SET title='" . $this->title . "', body='" . $this->content . "', userId='" . $this->author . "', createdAt='" . $this->date . "' WHERE id=" . $this->id;
        } else {
            $sql = "INSERT INTO posts (title, body, userId, createdAt) VALUES ('" . $this->title . "', '" . $this->content . "', '" . $this->author . "', '" . $this->date . "')";
        }
    
        if ($conn->query($sql) === TRUE) {
            echo "L'article a été sauvegardé avec succès.";
        } else {
            echo "Erreur lors de la sauvegarde de l'article : " . $conn->error;
        }
    }
    

    public static function getAll($conn) {
        $sql = "SELECT id, title, body, userId, createdAt FROM posts";
        $result = $conn->query($sql);
        $articles = array();
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Crée un nouvel objet Article en utilisant les données de la base de données
                $article = new Article($row['id'], $row["title"], $row["body"], $row["userId"], $row["createdAt"]);
                // Définit l'ID de l'article à partir de la base de données
                $article->id = $row["id"];
                // Ajoute l'article à la liste des articles
                $articles[] = $article;
            }
        }
    
        return $articles;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAuthor() {
         $pdo = new PDO("mysql:host=localhost;dbname=blog_database", "root", "");
        
         $sql = "SELECT name FROM user WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->author]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return $result['name'];
        } else {
            return '';
        }
    }
    

    public function getDate() {
        return $this->date;
    }

    public function getContent() {
        return $this->content;
    }

    public function getId() {
        return $this->id;
    }

     // Méthode pour définir l'ID de l'article
     public function setId($id) {
        $this->id = $id;
    }

     // Méthode pour définir le titre de l'article
     public function setTitle($title) {
        $this->title = $title;
    }

     // Méthode pour définir le contenu de l'article
     public function setContent($content) {
        $this->content = $content;
    }
    
    // Méthode statique pour récupérer un article par son identifiant
    public static function getById($conn, $article_id) {
        // Préparation de la requête SQL
        $sql = "SELECT * FROM posts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $article_id); // Utilisation du type "i" pour indiquer un entier en liaison de paramètre
        $stmt->execute();

        // Récupération du résultat de la requête
        $result = $stmt->get_result();

        // Vérification s'il y a des résultats
        if ($result->num_rows == 1) {
            // Extraction des données de l'article
            $row = $result->fetch_assoc();
            $article = new Article($row['id'], $row['title'], $row['body'], $row['userId'], $row['createdAt']);
            $article->getId($row['id']); // Définir l'ID de l'article
            return $article;
        } else {
            return null; // Retourner null si aucun article trouvé
        }
    }
    
}



?>
