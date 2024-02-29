<?php
session_start();

include_once("includes/autoload.php");
include_once("includes/db.php"); 
include_once("classes/article.php"); 
include_once("classes/comment.php");
include_once("templates/header.php");

if(isset($_GET['id']) && !empty($_GET['id'])) {
   
    $articleId = $_GET['id'];
    
    function getArticleDetailsFromDatabase($articleId) {
        $conn = new mysqli("localhost", "root", "", "blog_database");
    
        $sql = "SELECT * FROM posts WHERE id = $articleId";
    
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row; 
        } else {
            return false; 
        }
    }

    $articleDetails = getArticleDetailsFromDatabase($articleId);

if ($articleDetails) {
    ?>
    <div class="row">
        <div class="col-md-9">
            <h2><?php echo strtoupper($articleDetails['title']); ?></h2>
            <p>Auteur: <?php echo $articleDetails['userId']; ?></p>
            <p>Date: <?php echo $articleDetails['createdAt']; ?></p>
            <p>Article: <?php echo $articleDetails['body']; ?></p>
        </div>
        <div class="col-md-3">
            <?php
            function getCommentsForArticle($articleId) {
                $conn = new mysqli("localhost", "root", "", "blog_database");
    
                if ($conn->connect_error) {
                    die("La connexion à la base de données a échoué: " . $conn->connect_error);
                }
    
                $sql = "SELECT * FROM comments WHERE postId = $articleId";
    
                $result = $conn->query($sql);
    
                if ($result === false) {
                    die("Erreur lors de l'exécution de la requête: " . $conn->error);
                }
    
                $comments = array();
    
                while ($row = $result->fetch_assoc()) {
                    $comments[] = $row;
                }
    
                $conn->close();
    
                return $comments;
            }
    
            $comments = getCommentsForArticle($articleId);
            if (!empty($comments)) {
                echo "<h3>Commentaires :</h3>";
                foreach ($comments as $comment) {
                    echo '<div style="background-color: #f2f2f2; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">';
                    echo "<p><strong>Auteur:</strong> " . $comment['email'] . "</p>";
                    echo "<p><strong>Date:</strong> " . $comment['createdAt'] . "</p>";
                    echo "<p><strong>Contenu:</strong> " . $comment['body'] . "</p>";
                    echo '</div>';
                }
            } else {
                echo "<p>Aucun commentaire pour cet article.</p>";
            }
}
    

            ?>
            <?php if (isset($_SESSION['user_connected'])): ?>
                <h3>Ajouter un commentaire</h3>
                <form action="add_comment.php" method="post">
                    <input type="hidden" name="article_id" value="<?php echo $articleId; ?>">
                    <div class="form-group">
                        <label for="comment_author">Nom:</label>
                        <input type="text" class="form-control" id="comment_author" name="comment_author" required>
                    </div>
                    <div class="form-group">
                        <label for="comment_content">Contenu:</label>
                        <textarea class="form-control" id="comment_content" name="comment_content" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mb-5 mt-3">Ajouter le commentaire</button>
                </form>
            <?php else: ?>
                <p>Connectez-vous pour ajouter un commentaire.</p>
            <?php endif; ?>
            <?php // Vérifier si les données du formulaire sont soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de configuration de la base de données et la classe Comment
    include_once("includes/db.php");
    include_once("classes/comment.php");

    // Récupérer les données du formulaire
    $articleId = $_POST["postId"];
    $content = $_POST["body"];
    $author = $_SESSION["email"]; 
    $date = date("Y-m-d H:i:s"); 

    // Créer un nouvel objet Comment
    $comment = new Comment($articleId, $content, $author, $date);

    // Enregistrer le commentaire dans la base de données
    $comment->save($conn); // Assurez-vous que $conn est une connexion valide à votre base de données

    // Rediriger vers la page de l'article avec un message de confirmation
    header("Location: article.php?id=" . $articleId);
    exit();
} else {
    
}?>
        </div>
    </div>
    <?php
} else {
    echo "Pas d'article trouvé";
}

include_once("templates/footer.php");
?>