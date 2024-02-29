<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_connected']) || $_SESSION['user_connected'] !== true) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php");
    exit();
}

// Vérifier si les données du formulaire sont soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de configuration de la base de données et la classe Comment
    include_once("includes/db.php");
    include_once("classes/comment.php");

    // Récupérer les données du formulaire
    $articleId = $_POST["article_id"];
    $content = $_POST["comment_content"];
    $author = $_SESSION["email"];
    $date = date("Y-m-d H:i:s");

    // Créer un nouvel objet Comment
    $comment = new Comment($articleId, $content, $author, $date);

    // Enregistrer le commentaire dans la base de données
    $comment->save($conn);

    // Rediriger vers la page de l'article avec un message de confirmation
    header("Location: articles.php?id=". $articleId);
    exit();
} else {
    // Rediriger vers la page d'accueil si les données du formulaire ne sont pas soumises
    header("Location: index.php");
    exit();
}
?>
