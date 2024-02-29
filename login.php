<?php
session_start();
// Inclusion du fichier de configuration de la base de données
include_once("includes/db.php");


// Initialisation de la variable de connexion de l'utilisateur
$user_connected = false;

// Vérification de la méthode de requête HTTP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Requête pour vérifier les informations de connexion de l'utilisateur
    $sql = "SELECT id, email, password FROM user WHERE email = '$email'";
    $result = $conn->query($sql);

    // Vérification si l'utilisateur existe dans la base de données
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Vérification du mot de passe avec password_verify
        if (password_verify($password, $row["password"])) {
            // Connexion réussie
            echo "Connexion réussie !";

            // Définition de la session utilisateur comme connectée
            $_SESSION['user_connected'] = true;
            $_SESSION['email'] = $email; // Stocke l'email dans la session
            $_SESSION['user_id'] = $row['id']; // Stocke l'ID de l'utilisateur dans la session

            // Requête pour récupérer le rôle de l'utilisateur
            $user_role_query = "SELECT role FROM user WHERE email = '$email'";
            $role_result = $conn->query($user_role_query);

            // Vérification si la requête a abouti
            if ($role_result && $role_result->num_rows > 0) {
                $user_role_row = $role_result->fetch_assoc();
                // Définition du rôle de l'utilisateur dans la session
                $_SESSION['user_role'] = $user_role_row['role'];
            } else {
                echo "Erreur lors de la récupération du rôle de l'utilisateur.";
            }

            // Redirection vers la page d'accueil après connexion
            header("Location: index.php");
            exit();
        } else {
            // Mot de passe incorrect
            echo "Mot de passe incorrect.";
        }
    } else {
        // Adresse email incorrecte
        echo "Adresse email incorrecte.";
    }
}

// Inclusion du fichier d'en-tête de la page
include_once("templates/header.php");
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <h2>Connexion</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
    </div>
</div>

<?php
// Inclusion du fichier de pied de page
include_once("templates/footer.php");
?>
