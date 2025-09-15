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

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate__animated animate__fadeInUp">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="L'Enfant Noir">
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">L'Enfant Noir</h3>
                            <p class="book-author">Camara Laye</p>
                            <span class="book-status status-available">Disponible</span>
                            <a href="#" class="view-btn">Voir détails</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Une si longue lettre">
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">Une si longue lettre</h3>
                            <p class="book-author">Mariama Bâ</p>
                            <span class="book-status status-borrowed">Emprunté</span>
                            <a href="#" class="view-btn">Voir détails</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=698&q=80" alt="L'Aventure ambiguë">
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">L'Aventure ambiguë</h3>
                            <p class="book-author">Cheikh Hamidou Kane</p>
                            <span class="book-status status-available">Disponible</span>
                            <a href="#" class="view-btn">Voir détails</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1515098500754-2c6d0e3f2c26?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Sous l'Orage">
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">Sous l'Orage</h3>
                            <p class="book-author">Seydou Badian</p>
                            <span class="book-status status-available">Disponible</span>
                            <a href="#" class="view-btn">Voir détails</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plus empruntés Section -->
    <section class="py-5" id="popular-books">
        <div class="container">
            <div class="section-title">
                <h2><i class="fas fa-fire"></i> Les plus empruntés</h2>
                <p>Découvrez les ouvrages les plus populaires de notre bibliothèque</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate__animated animate__fadeInUp">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=628&q=80" alt="Le Monde s'effondre">
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">Le Monde s'effondre</h3>
                            <p class="book-author">Chinua Achebe</p>
                            <span class="book-status status-borrowed">Emprunté</span>
                            <a href="#" class="view-btn">Voir détails</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1589998059171-988d887df646?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1176&q=80" alt="Amkoullel l'enfant peul">
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">Amkoullel l'enfant peul</h3>
                            <p class="book-author">Amadou Hampâté Bâ</p>
                            <span class="book-status status-available">Disponible</span>
                            <a href="#" class="view-btn">Voir détails</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1516979187457-637abb4f9353?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="La Promesse des fleurs">
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">La Promesse des fleurs</h3>
                            <p class="book-author">Nuruddin Farah</p>
                            <span class="book-status status-available">Disponible</span>
                            <a href="#" class="view-btn">Voir détails</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1516975080664-ed2fc6a32937?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Les Bouts de Bois de Dieu">
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">Les Bouts de Bois de Dieu</h3>
                            <p class="book-author">Ousmane Sembène</p>
                            <span class="book-status status-borrowed">Emprunté</span>
                            <a href="#" class="view-btn">Voir détails</a>
                        </div>
                    </div>
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