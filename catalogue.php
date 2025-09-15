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
    <link rel="stylesheet" href="css/Style.css">
</head>
<body>
    <!-- Barre de navigation -->
    <?php include_once "header.php" ?>

    <main class="container mb-5">
        <section class="filter-section">
            <h2 class="section-title fw-bold">
                <i class="fas fa-filter me-2"></i>Filtres de recherche
            </h2>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="category" class="form-label fw-semibold">Catégorie</label>
                    <select class="form-select" id="category">
                        <option selected>Toutes les catégories</option>
                        <option value="fiction">Fiction</option>
                        <option value="history">Histoire</option>
                        <option value="science">Science</option>
                        <option value="biography">Biographie</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="author" class="form-label fw-semibold">Auteur</label>
                    <select class="form-select" id="author">
                        <option selected>Tous les auteurs</option>
                        <option value="achebe">Chinua Achebe</option>
                        <option value="ba">Mariama Bâ</option>
                        <option value="laye">Camara Laye</option>
                        <option value="kourouma">Ahmadou Kourouma</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="availability" class="form-label fw-semibold">Disponibilité</label>
                    <select class="form-select" id="availability">
                        <option selected>Tous</option>
                        <option value="available">Disponible</option>
                        <option value="borrowed">Emprunté</option>
                    </select>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-outline-secondary me-2">
                    <i class="fas fa-times me-1"></i>Réinitialiser
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-search me-1"></i>Appliquer les filtres
                </button>
            </div>
        </section>
        
        <section class="books-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0 text-white">Nos livres</h3>
                <div class="d-flex align-items-center">
                    <span class="me-2 fw-semibold">Trier par:</span>
                    <select class="form-select form-select-sm w-auto">
                        <option selected>Plus récents</option>
                        <option>Plus populaires</option>
                        <option>A-Z</option>
                        <option>Z-A</option>
                    </select>
                </div>
            </div>
            
            <div class="row g-4">
                <!-- Book 1 -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="book-image w-100" alt="Things Fall Apart by Chinua Achebe">
                        <div class="card-body">
                            <h5 class="book-title">Things Fall Apart</h5>
                            <p class="book-author">Chinua Achebe</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">Disponible</span>
                                <a href="#" class="btn btn-details">Plus d’infos</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Book 2 -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="book-image w-100" alt="Une si longue lettre de Mariama Bâ">
                        <div class="card-body">
                            <h5 class="book-title">Une si longue lettre</h5>
                            <p class="book-author">Mariama Bâ</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-danger text-dark">Indisponible</span>
                                <a href="#" class="btn btn-details">Plus d’infos</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Book 3 -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="book-image w-100" alt="L'Enfant noir de Camara Laye">
                        <div class="card-body">
                            <h5 class="book-title">L'Enfant noir</h5>
                            <p class="book-author">Camara Laye</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">Disponible</span>
                                <a href="#" class="btn btn-details">Plus d’infos</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Book 4 -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="book-image w-100" alt="Les Soleils des indépendances d'Ahmadou Kourouma">
                        <div class="card-body">
                            <h5 class="book-title">Les Soleils des indépendances</h5>
                            <p class="book-author">Ahmadou Kourouma</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">Disponible</span>
                                <a href="#" class="btn btn-details">Plus d’infos</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Book 5 -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="book-image w-100" alt="Americanah de Chimamanda Ngozi Adichie">
                        <div class="card-body">
                            <h5 class="book-title">Americanah</h5>
                            <p class="book-author">Chimamanda N. Adichie</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">Disponible</span>
                                <a href="#" class="btn btn-details">Plus d’infos</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Book 6 -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="book-image w-100" alt="Half of a Yellow Sun de Chimamanda Ngozi Adichie">
                        <div class="card-body">
                            <h5 class="book-title">Half of a Yellow Sun</h5>
                            <p class="book-author">Chimamanda N. Adichie</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-warning text-dark">Emprunté</span>
                                <a href="#" class="btn btn-details">Plus d’infos</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Book 7 -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="book-image w-100" alt="Le Monde s'effondre de Chinua Achebe">
                        <div class="card-body">
                            <h5 class="book-title">Le Monde s'effondre</h5>
                            <p class="book-author">Chinua Achebe</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">Disponible</span>
                                <a href="#" class="btn btn-details">Plus d’infos</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Book 8 -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="book-card">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="book-image w-100" alt="L'Aventure ambiguë de Cheikh Hamidou Kane">
                        <div class="card-body">
                            <h5 class="book-title">L'Aventure ambiguë</h5>
                            <p class="book-author">Cheikh Hamidou Kane</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">Disponible</span>
                                <a href="#" class="btn btn-details">Voir plus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <nav class="mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Suivant</a>
                    </li>
                </ul>
            </nav>
        </section>
    </main>

    <!-- Pied de page -->
    <?php include_once "footer.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>