<?php 
// // Démarrage de la session
// session_start();

// Initialisation des variables
$user_connected = false;
$user_role = 'user'; 

// Vérification de la connexion de l'utilisateur
if (isset($_SESSION['user_connected']) && $_SESSION['user_connected'] == true) {
    $user_connected = true;
    // Vérification du rôle de l'utilisateur
    if (isset($_SESSION['user_role'])) {
        $user_role = $_SESSION['user_role'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Mon Blog</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Accueil <span class="sr-only">(current)</span></a>
            </li>
            <?php
            
            // Affichage des liens de navigation en fonction de l'état de connexion de l'utilisateur et de son rôle
            if ($user_connected === true) {
                echo '<li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                      </li>';
                        
                if ($user_role === 'admin') {
                    echo '<li class="nav-item">
                            <a class="nav-link" href="admin.php">Administration</a>
                          </li>';
                }
            } else {
                echo '<li class="nav-item">
                        <a class="nav-link" href="login.php">Connexion</a>
                      </li>';
            }
            ?>
        </ul>
    </div>
</nav>

<div class="container mt-4 bg-light">

