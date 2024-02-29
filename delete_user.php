<?php
session_start();

// Inclure les fichiers requis
include_once("includes/autoload.php");
include_once("includes/db.php");
include_once("classes/user.php");
include_once("templates/header.php");

// Vérification de la connexion de l'utilisateur et de son rôle d'administrateur
if ($user_connected !== true || strtolower($user_role) !== 'admin') {
    header("Location: index.php"); 
    exit(); 
}

// Vérifiez si l'ID de l'utilisateur à supprimer est présent dans l'URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Supprimez l'utilisateur avec l'ID spécifié de la base de données
    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    // Redirigez l'utilisateur vers la page d'administration des utilisateurs avec un message de confirmation
    header("Location: admin.php?deleted=true");
    exit();
} else {
    // Redirigez l'utilisateur vers la page d'administration des utilisateurs si aucun ID n'est spécifié
    header("Location: admin.php");
    exit();
}
?>
