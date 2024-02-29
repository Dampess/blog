<?php
// Démarre la session
session_start();

// Efface toutes les variables de session
$_SESSION = array();

// Détruit la session
session_destroy();

// Redirige vers la page d'accueil
header("Location: index.php");
exit();
?>
