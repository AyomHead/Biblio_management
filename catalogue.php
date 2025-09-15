<?php session_start(); ?>
<?php
// Inclure le fichier de configuration de la base de données
require_once 'config.php';

// Récupérer les paramètres de filtrage
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$author_filter = isset($_GET['author']) ? $_GET['author'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'created_at DESC';

// Paramètres de pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$books_per_page = 12;
$offset = ($page - 1) * $books_per_page;

// Construire la requête de base
$query = "SELECT * FROM books WHERE 1=1";
$count_query = "SELECT COUNT(*) as total FROM books WHERE 1=1";
$params = [];
$count_params = [];

// Appliquer les filtres
if (!empty($category_filter)) {
    $query .= " AND category = :category";
    $count_query .= " AND category = :category";
    $params[':category'] = $category_filter;
    $count_params[':category'] = $category_filter;
}

if (!empty($author_filter)) {
    $query .= " AND author LIKE :author";
    $count_query .= " AND author LIKE :author";
    $params[':author'] = '%' . $author_filter . '%';
    $count_params[':author'] = '%' . $author_filter . '%';
}

if (!empty($status_filter)) {
    $query .= " AND status = :status";
    $count_query .= " AND status = :status";
    $params[':status'] = $status_filter;
    $count_params[':status'] = $status_filter;
}

// Appliquer le tri
$query .= " ORDER BY " . $sort_by;

// Ajouter la limite pour la pagination
$query .= " LIMIT :limit OFFSET :offset";
$params[':limit'] = $books_per_page;
$params[':offset'] = $offset;

// Exécuter la requête
try {
    // Compter le nombre total de livres
    $count_stmt = $pdo->prepare($count_query);
    $count_stmt->execute($count_params);
    $total_books = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_books / $books_per_page);
    
    // Récupérer les livres pour la page actuelle
    $stmt = $pdo->prepare($query);
    
    // Liaison des paramètres pour la pagination
    $stmt->bindValue(':limit', $books_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    // Liaison des autres paramètres
    foreach ($params as $key => $value) {
        if ($key !== ':limit' && $key !== ':offset') {
            $stmt->bindValue($key, $value);
        }
    }
    
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les catégories et auteurs uniques pour les filtres
    $categories_query = "SELECT DISTINCT category FROM books WHERE category IS NOT NULL ORDER BY category";
    $categories_stmt = $pdo->query($categories_query);
    $categories = $categories_stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $authors_query = "SELECT DISTINCT author FROM books ORDER BY author";
    $authors_stmt = $pdo->query($authors_query);
    $authors = $authors_stmt->fetchAll(PDO::FETCH_COLUMN);
    
} catch (PDOException $e) {
    $books = [];
    $categories = [];
    $authors = [];
    $total_books = 0;
    $total_pages = 1;
    $error = "Erreur lors de la récupération des livres: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de livres - Bibliothèque Nationale du Bénin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/catalogue.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/Style.css">
    <style>
        .filter-title {
            color: #03d476 !important;
            margin-bottom: 1.5rem;
        }
        
        /* Styles pour la pagination active */
        .pagination .page-item.active .page-link {
            background-color: #03d476;
            border-color: #03d476;
        }
        
        .pagination .page-link {
            color: #03d476;
        }
        
        .pagination .page-link:hover {
            color: #028a54;
        }
    </style>
</head>
<body>
    <!-- Barre de navigation -->
    <?php include_once "header.php" ?>

    <main class="container mb-5">
        <section class="filter-section">
            <h2 class="fw-bold text-start filter-title">
                <i class="fas fa-filter me-2"></i>Filtres de recherche
            </h2>
            
            <form method="GET" action="">
                <!-- Ajouter les paramètres de tri et page cachés -->
                <input type="hidden" name="sort" value="<?= htmlspecialchars($sort_by) ?>">
                <input type="hidden" name="page" value="1">
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="category" class="form-label fw-semibold">Catégorie</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category) ?>" <?= $category_filter == $category ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="author" class="form-label fw-semibold">Auteur</label>
                        <select class="form-select" id="author" name="author">
                            <option value="">Tous les auteurs</option>
                            <?php foreach ($authors as $author): ?>
                                <option value="<?= htmlspecialchars($author) ?>" <?= $author_filter == $author ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($author) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label fw-semibold">Disponibilité</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tous</option>
                            <option value="DISPONIBLE" <?= $status_filter == 'DISPONIBLE' ? 'selected' : '' ?>>Disponible</option>
                            <option value="INDISPONIBLE" <?= $status_filter == 'INDISPONIBLE' ? 'selected' : '' ?>>Indisponible</option>
                        </select>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    <a href="catalogue.php" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-times me-1"></i>Réinitialiser
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Appliquer les filtres
                    </button>
                </div>
            </form>
        </section>
        
        <section class="books-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0 text-white">Nos livres (<?= $total_books ?>)</h3>
                <div class="d-flex align-items-center">
                    <span class="me-2 fw-semibold">Trier par:</span>
                    <select class="form-select form-select-sm w-auto" id="sort-select">
                        <option value="created_at DESC" <?= $sort_by == 'created_at DESC' ? 'selected' : '' ?>>Plus récents</option>
                        <option value="borrow_count DESC" <?= $sort_by == 'borrow_count DESC' ? 'selected' : '' ?>>Plus populaires</option>
                        <option value="title ASC" <?= $sort_by == 'title ASC' ? 'selected' : '' ?>>A-Z</option>
                        <option value="title DESC" <?= $sort_by == 'title DESC' ? 'selected' : '' ?>>Z-A</option>
                    </select>
                </div>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <div class="row g-4">
                <?php if (!empty($books)): ?>
                    <?php 
                    $delay = 0;
                    foreach ($books as $book): 
                    ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="book-card animate__animated animate__fadeInUp" style="animation-delay: <?php echo $delay; ?>s;">
                                <div class="book-image">
                                    <img src="<?php echo !empty($book['cover_image']) ? htmlspecialchars($book['cover_image']) : 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80'; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                                </div>
                                <div class="book-info">
                                    <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                                    <p class="book-author"><?php echo htmlspecialchars($book['author']); ?></p>
                                    

                                    <?php if (!empty($book['category'])): ?>
                                        <span class="book-category-badge"><?php echo htmlspecialchars($book['category']); ?></span>
                                    <?php endif; ?>
                                    
                                    <span class="book-status status-<?php echo $book['status'] == 'DISPONIBLE' ? 'available' : 'borrowed'; ?>">
                                        <?php echo $book['status'] == 'DISPONIBLE' ? 'Disponible' : 'Indisponible'; ?>
                                    </span>
                                    <a href="book_details.php?id=<?php echo $book['id']; ?>" class="view-btn">Voir détails</a>
                                </div>
                            </div>
                        </div>
                    <?php 
                    $delay += 0.1;
                    endforeach; 
                    ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-book-open fa-3x mb-3"></i>
                            <h4>Aucun livre trouvé</h4>
                            <p>Aucun livre ne correspond à vos critères de recherche.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav class="mt-5">
                <ul class="pagination justify-content-center">
                    <!-- Lien précédent -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= generatePageUrl($page - 1) ?>" tabindex="-1" aria-disabled="true">Précédent</a>
                    </li>
                    
                    <!-- Liens des pages -->
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= generatePageUrl($i) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <!-- Lien suivant -->
                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= generatePageUrl($page + 1) ?>">Suivant</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </section>
    </main>

    <!-- Pied de page -->
    <?php include_once "footer.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript pour le tri dynamique
        document.getElementById('sort-select').addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', this.value);
            url.searchParams.set('page', 1); // Retour à la première page lors du changement de tri
            window.location.href = url.toString();
        });
    </script>
</body>
</html>

<?php
// Fonction pour générer les URLs de pagination
function generatePageUrl($pageNum) {
    $params = $_GET;
    $params['page'] = $pageNum;
    return 'catalogue.php?' . http_build_query($params);
}
?>