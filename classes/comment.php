<?php
class Comment {
    private $id;
    private $articleId;
    private $content;
    private $author;
    private $date;

    
    public function __construct($articleId, $content, $author, $date) {
        $this->articleId = $articleId;
        $this->content = $content;
        $this->author = $author;
        $this->date = $date;
    }


    public function save($conn) {
        // Préparer la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO comments (postId, body, email, createdAt) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $this->articleId, $this->content, $this->author, $this->date);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Le commentaire a été ajouté avec succès
            return true;
        } else {
            // Erreur lors de l'ajout du commentaire
            return false;
        }
    }

    

    
}
?>
