<?php
session_start();

include_once("includes/autoload.php"); 
include_once("includes/db.php");

// Vérifiez si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['user_connected']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php"); // Redirection vers la page d'accueil si l'utilisateur n'est pas connecté en tant qu'administrateur
    exit();
}

// Initialisation des variables
$email = $password = $role = "";
$email_err = $password_err = $role_err = "";

// Traitement du formulaire lors de la soumission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation de l'e-mail
    if (empty(trim($_POST["email"]))) {
        $email_err = "Veuillez entrer une adresse e-mail.";
    } else {
        $email = trim($_POST["email"]);
    }
    
    // Validation du mot de passe
    if (empty(trim($_POST["password"]))) {
        $password_err = "Veuillez entrer un mot de passe.";     
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validation du rôle
    if (empty(trim($_POST["role"]))) {
        $role_err = "Veuillez sélectionner un rôle.";
    } else {
        $role = trim($_POST["role"]);
    }
    
    // Vérifier s'il n'y a pas d'erreurs de saisie avant d'insérer dans la base de données
    if (empty($email_err) && empty($password_err) && empty($role_err)) {
        // Hash du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Requête SQL pour insérer un nouvel utilisateur dans la base de données
        $sql = "INSERT INTO user (email, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $hashed_password, $role);
        
        // Exécution de la requête
        if ($stmt->execute()) {
            // Redirection vers la page d'administration des utilisateurs avec un message de confirmation
            header("Location: admin.php?added=true");
            exit();
        } else {
            echo "Erreur lors de l'ajout de l'utilisateur.";
        }
        
        // Fermeture de la déclaration
        $stmt->close();
    }
    
    // Fermeture de la connexion
    $conn->close();
}

include_once("templates/header.php")
?>


    <div class="container">
        <h2 class="mt-4">Ajouter un nouvel utilisateur</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>E-mail :</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Mot de passe :</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Rôle :</label>
                <select name="role" class="form-control <?php echo (!empty($role_err)) ? 'is-invalid' : ''; ?>">
                    <option value="" selected disabled>Sélectionner un rôle</option>
                    <option value="admin">Admin</option>
                    <option value="user">Utilisateur</option>
                </select>
                <span class="invalid-feedback"><?php echo $role_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Ajouter">
                <a href="admin_users.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>    
<?phpinclude_once("templates/footer.php")
?>