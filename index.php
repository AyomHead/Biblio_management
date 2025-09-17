<?php session_start(); ?>
<?php
// Inclure le fichier de configuration de la base de données
require_once 'includes/config.php';

// Récupérer les livres depuis la base de données
try {
    $query = "SELECT * FROM books ORDER BY created_at DESC LIMIT 4";
    $stmt = $pdo->query($query);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $books = [];
    $error = "Erreur lors de la récupération des livres: " . $e->getMessage();
}

// Récupérer les livres les plus empruntés
try {

    // Créer la colonne borrow_count si elle n'existe pas
    $popular_query = "SELECT * FROM books ORDER BY borrow_count DESC LIMIT 4";
    $popular_stmt = $pdo->query($popular_query);
    $popular_books = $popular_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $popular_books = [];
    $popular_error = "Erreur lors de la récupération des livres populaires: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque Nationale du Bénin - Accueil</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/Style.css">
</head>
<body>
    <!-- Barre de navigation -->
    <?php include_once "header.php" ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content animate__animated animate__fadeIn">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h1>Bibliothèque Nationale du Bénin</h1>
                    <p>Découvrez notre vaste collection de livres et ressources documentaires</p>
                    
                    <div class="search-bar">
                        <input type="text" placeholder="Rechercher un livre, un auteur ou une catégorie...">
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    
                    <div class="hero-btns mt-4">
                        <a href="catalogue.html" class="btn-custom btn-primary-custom">
                            <i class="fas fa-book-open me-2"></i> Explorer le Catalogue
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nouveautés Section -->
        <section class="py-5" id="new-books">
            <div class="container">
                <div class="section-title">
                    <h2><i class="fas fa-star"></i> Nouveautés</h2>
                    <p>Découvrez les derniers ouvrages ajoutés à notre collection</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
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
                                        <a href="detail.php?id=<?php echo $book['id']; ?>" class="view-btn">Voir détails</a>
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

                <!-- Plus empruntés Section -->
                <section class="py-5" id="popular-books">
                    <div class="container">
                        <div class="section-title">
                            <h2><i class="fas fa-fire"></i> Les plus empruntés</h2>
                            <p>Découvrez les ouvrages les plus populaires de notre bibliothèque</p>
                        </div>

                        <?php if (!empty($popular_error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $popular_error; ?>
                            </div>
                        <?php endif; ?>

                        <div class="row g-4">
                            <?php if (!empty($popular_books)): ?>
                                <?php 
                                $delay = 0;
                                foreach ($popular_books as $book): 
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
                                                <a href="detail.php?id=<?php echo $book['id']; ?>" class="view-btn">Voir détails</a>
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
                    </div>
                </div>
        </section>

    <!-- Pied de page -->
    <?php include_once "footer.php" ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>