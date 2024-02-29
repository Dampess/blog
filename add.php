<?php
session_start();

// Inclusion des fichiers requis
include_once("includes/autoload.php"); 
include_once("includes/db.php");
include_once("classes/article.php");
include_once("templates/header.php");

// Vérification de la connexion de l'utilisateur et de son rôle d'administrateur
if (!isset($_SESSION['user_connected']) || strtolower($_SESSION['user_role']) !== 'admin') {
    // Redirection vers la page d'accueil si l'utilisateur n'est pas connecté ou s'il n'a pas le rôle d'administrateur
    header("Location: index.php"); 
    exit(); 
}

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $title = htmlspecialchars($_POST["title"]);
    $content = htmlspecialchars($_POST["content"]);
    $author = $_SESSION["email"]; 
    $date = date("Y-m-d H:i:s");
    
    // Récupération de l'ID de l'utilisateur connecté
    $userId = $_SESSION["user_id"];

    // Préparation de la requête d'insertion
    $sql = "INSERT INTO posts (title, body, userId, createdAt) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $title, $content, $userId, $date);
    
    try {
        // Exécution de la requête préparée
        $stmt->execute();
        echo "L'article a été sauvegardé avec succès.";
    } catch (Exception $e) {
        // Gestion des erreurs lors de la sauvegarde de l'article
        echo "Erreur lors de la sauvegarde de l'article : " . $e->getMessage();
    }
    
    // Fermeture du statement
    $stmt->close();
}
?>

<div class="row">
    <div class="col-md-8">
        <h2>Ajouter un nouvel article</h2>
        <!-- Formulaire pour ajouter un nouvel article -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">Contenu</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter l'article</button>
        </form>
    </div>
</div>

<?php include_once("templates/footer.php"); ?>
