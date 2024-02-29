<?php
session_start();

include_once("includes/autoload.php"); 
include_once("includes/db.php");
include_once("templates/header.php");

// Vérification de la connexion de l'utilisateur et de son rôle d'administrateur
if ($user_connected !== true || strtolower($user_role) !== 'admin') {
    header("Location: login.php"); 
    exit(); 
}

include_once("classes/article.php");
include_once("classes/user.php");

// Récupération de tous les articles et tous les utilisateurs depuis la base de données
$articles = Article::getAll($conn); 
$users = User::getAll($conn); 
?>

<div class="row">
    <div class="col-md-6">
        <!-- Gestion des articles -->
        <h2>Liste des articles</h2>
        <a href="add.php" class="btn btn-success mb-3">Ajouter un nouvel article</a>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Titre</th>
                    <th scope="col">Auteur</th>
                    <th scope="col">Date</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?php echo $article->getTitle(); ?></td>
                        <td><?php echo $article->getAuthor(); ?></td>
                        <td><?php echo $article->getDate(); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $article->getId(); ?>" class="btn btn-primary btn-sm">Modifier</a>
                            <a href="delete.php?id=<?php echo $article->getId(); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')" class="btn btn-danger btn-sm">Supprimer</a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <!-- Gestion des utilisateurs -->
        <h2>Liste des utilisateurs</h2>
        <a href="add_user.php" class="btn btn-success mb-3">Ajouter un nouvel utilisateur</a>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Email</th>
                    <th scope="col">Rôle</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user->getId(); ?></td>
            <td><?php echo $user->getEmail(); ?></td>
            <td><?php echo $user->getRole(); ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $user->getId(); ?>" class="btn btn-primary btn-sm">Modifier</a>
                <a href="delete_user.php?id=<?php echo $user->getId(); ?>" class="btn btn-danger btn-sm">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

            </tbody>
        </table>
    </div>
</div>

<?php 
// Inclusion du pied de page
include_once("templates/footer.php"); 
?>
