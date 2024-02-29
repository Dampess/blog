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



// Vérifier si l'ID de l'utilisateur à modifier est passé en paramètre dans l'URL
if (!isset($_GET['id'])) {
    // Redirigez l'utilisateur vers la page d'administration si aucun ID n'est spécifié
    header("Location: admin.php");
    exit();
}

// Récupérer l'ID de l'utilisateur à modifier depuis l'URL
$user_id = $_GET['id'];

// Obtenez l'objet User à partir de l'ID
$user = User::getById($conn, $user_id);

// Vérifiez si l'objet User a été correctement obtenu
if ($user) {
    // Récupérez l'email de l'utilisateur
    $email = $user->getEmail();

    // Utilisez l'email de l'utilisateur pour l'affichage ou tout autre traitement nécessaire
    echo "Email de l'utilisateur: " . $email;
} else {
    // Gérez le cas où aucun utilisateur n'est trouvé
    echo "Utilisateur non trouvé.";
}


// Vérifier si le formulaire de modification a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérifier si le nouveau rôle a été sélectionné
    if (isset($_POST['role'])) {
        // Récupérer le nouveau rôle depuis le formulaire
        $new_role = $_POST['role'];

        // Mettre à jour le rôle de l'utilisateur dans la base de données
        $sql = "UPDATE user SET role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_role, $user_id);

        if ($stmt->execute()) {
            // Rediriger l'utilisateur vers la page d'administration après la mise à jour
            header("Location: admin.php");
            exit();
        } else {
            // Gérer les erreurs lors de la mise à jour du rôle
            echo "Erreur lors de la mise à jour du rôle de l'utilisateur : " . $stmt->error;
        }
    }
}

// Récupérer les informations sur l'utilisateur à partir de la base de données
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Récupérer les données de l'utilisateur
    $user = $result->fetch_assoc();
    
} else {
    // // Rediriger l'utilisateur vers la page d'administration si aucun utilisateur correspondant n'est trouvé
    // header("Location: admin.php");
    // exit();
}
?>

<!-- Formulaire de modification du rôle de l'utilisateur -->
<h2>Modifier le rôle de l'utilisateur</h2>
<form method="post">
    <div class="form-group">
        <label for="role">Nouveau rôle :</label>
        <select name="role" id="role" class="form-control">
            <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>Utilisateur</option>
            <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Administrateur</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>

<?php include_once("templates/footer.php"); ?>
