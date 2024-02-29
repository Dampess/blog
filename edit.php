<?php

session_start();

// Inclusion des fichiers requis
include_once("includes/autoload.php");
include_once("includes/db.php");
include_once("templates/header.php");

if ($user_connected !== true || strtolower($user_role) !== 'admin') {
    header("Location: index.php"); 
    exit(); 
}




// Vérification si l'id de l'article est passé en paramètre
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Récupération de l'article à partir de la base de données
    $article = Article::getById($conn, $article_id);

    // Vérification si l'article existe
    if ($article) {
        // Vérification des autorisations de modification de l'article
        if ($_SESSION['user_role'] === 'admin' || $article->user::getId() === $_SESSION['user_id']) {
            // Traitement du formulaire de modification
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $title = $_POST['title'];
                $content = $_POST['content'];

                // Modification de l'article
                $article->setTitle($title);
                $article->setContent($content);
                $article->save($conn);
                
                ob_start();

                $article = Article::getById($conn, $article_id);

            // Vérifiez si l'objet Article a été correctement obtenu
          if ($article) {
                // Récupérez l'ID de l'article
                 $articleId = $article->getId();

                // Redirigez l'utilisateur vers la page articles.php avec l'ID de l'article dans l'URL
                 header("Location: articles.php?id=" . $articleId);
                 exit();
            } else {
                 // Gérez le cas où aucun article n'est trouvé
         echo "Article non trouvé.";
}
            }
        } else {
            // Redirection vers la page d'accueil si l'utilisateur n'a pas les autorisations nécessaires
            header("Location: index.php");
            exit();
        }
    } else {
        // Redirection vers la page d'accueil si l'article n'existe pas
        header("Location: index.php");
        exit();
    }
} else {
    // Redirection vers la page d'accueil si l'id de l'article n'est pas spécifié
    header("Location: index.php");
    exit();
}
?>

<div class="container">
    <h2>Modifier l'article</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Titre :</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo $article->getTitle(); ?>">
        </div>
        <div class="form-group">
            <label for="content">Contenu :</label>
            <textarea class="form-control" id="content" name="content"><?php echo $article->getContent(); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<?php
// Inclusion du pied de page
include_once("templates/footer.php");
?>
