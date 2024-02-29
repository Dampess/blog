<?php
session_start();

include_once("includes/autoload.php"); 
include_once("includes/db.php");
include_once("classes/article");
include_once("templates/header.php");

// Vérification de la connexion de l'utilisateur et de son rôle d'administrateur
if ($user_connected !== true || strtolower($user_role) !== 'admin') {
    header("Location: index.php"); 
    exit(); 
}

// Vérifiez si l'ID de l'article à supprimer est présent dans l'URL
if(isset($_GET['id'])) {
    // Récupérez l'ID de l'article à supprimer
    $article_id = $_GET['id'];

    // Connexion à la base de données (à remplacer par vos informations de connexion)
    $conn = new mysqli("localhost", "root", "", "blog_database");

    // Vérifiez la connexion à la base de données
    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données: " . $conn->connect_error);
    }

    // Requête SQL pour supprimer l'article
    $sql = "DELETE FROM posts WHERE id = $article_id";

    if ($conn->query($sql) === TRUE) {
        echo "L'article a été supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression de l'article : " . $conn->error;
    }

    // Fermez la connexion à la base de données
    $conn->close();
} else {
    echo "L'ID de l'article à supprimer n'a pas été spécifié.";
}

// Redirigez l'utilisateur vers une page appropriée après la suppression de l'article
header("Location: admin.php");
exit();
?>
