<?php
session_start();
include_once("includes/autoload.php"); 
include_once("includes/db.php");
include_once("templates/header.php");

// Récupérer tous les articles
$articles = Article::getAll($conn);

?>

<h2 class="text-center mb-5">Derniers articles publiés</h2>


<div class="row">
    <?php
    // Trier les articles par date de publication (du plus récent au plus ancien)
    usort($articles, function($a, $b) {
        return strtotime($b->getDate()) - strtotime($a->getDate());
    });

    // Nombre d'articles à afficher par page
    $articlesPerPage = 12;

    // Calculer le nombre total de pages
    $totalPages = ceil(count($articles) / $articlesPerPage);

    // Récupérer le numéro de page à afficher
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    // Calculer l'index de départ pour les articles à afficher sur cette page
    $startIndex = ($page - 1) * $articlesPerPage;

    // Sélectionner les articles à afficher sur cette page
    $articlesToShow = array_slice($articles, $startIndex, $articlesPerPage);
    ?>
    
    <div class="row">
        <?php foreach ($articlesToShow as $article): ?>
            <div class="col-md-4 ">
                <div class="card mb-3 bg-dark">
                    <div class="card-body">
                        <h5 class="card-title text-white" style="text-transform: uppercase;"><?php echo $article->getTitle(); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $article->getAuthor(); ?> - <?php echo $article->getDate(); ?></h6>
                        <p class="card-text text-white"><?php echo $article->getContent(); ?></p>
                        <a href="articles.php?id=<?php echo $article->getId(); ?>" class="card-link">Lire la suite</a>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-md-12  ">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Précédent</span>
                        </a>
                    </li>

                    <!-- Afficher les numéros de page -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Suivant</span>
                        </a>
                    </li>
                   <?php $pageSuivante = $page + 1;
                        $pagePrecedente = $page - 1; ?>
                    <!-- Ajout de deux boutons -->
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $pageSuivante; ?>">Suivant</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $pagePrecedente; ?>">Précédent</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php include_once("templates/footer.php"); ?>
