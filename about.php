<?php session_start(); ?>
<?php
// Inclure le fichier de configuration de la base de données
require_once 'includes/config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - Bibliothèque Nationale du Bénin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/Style.css">
</head>
<body>
    <!-- Barre de navigation -->
    <?php include_once "header.php" ?>

    <!-- Hero Section -->
    <section class="hero-section" style="background: linear-gradient(to bottom, rgba(1, 50, 68, 0.7), rgba(1, 50, 68, 0.9)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');">
        <div class="container hero-content">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h1>À propos de nous</h1>
                    <p>Découvrez l'histoire et la mission de la Bibliothèque Nationale du Bénin</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenu principal -->
    <main class="container py-5">
        <!-- Section Histoire -->
        <section class="mb-5">
            <div class="section-title">
                <h2><i class="fas fa-history me-2"></i>Notre Histoire</h2>
                <p>Découvrez notre parcours depuis la création</p>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>Fondée en 1975, la Bibliothèque Nationale du Bénin est l'institution patrimoniale la plus importante du pays en matière de conservation et de diffusion du savoir. Depuis près de cinq décennies, nous nous engageons à préserver le patrimoine documentaire béninois et à le rendre accessible à tous.</p>
                    <p>Notre institution a évolué au fil des années pour s'adapter aux nouvelles technologies et aux besoins changeants de nos usagers, tout en restant fidèle à sa mission fondamentale de promotion de la lecture et de la culture.</p>
                </div>
                <div class="col-md-6">
                    <div class="book-card">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Histoire de la bibliothèque">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Mission et Vision -->
        <section class="mb-5">
            <div class="section-title">
                <h2><i class="fas fa-bullseye me-2"></i>Mission & Vision</h2>
                <p>Nos objectifs et aspirations</p>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="book-card h-100">
                        <div class="book-info text-center">
                            <div class="icon-container mb-3" style="font-size: 2.5rem; color: #03d476;">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h3 class="book-title">Notre Mission</h3>
                            <p>Collecter, préserver et diffuser le patrimoine documentaire national pour garantir l'accès à l'information et à la connaissance pour tous les citoyens béninois.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="book-card h-100">
                        <div class="book-info text-center">
                            <div class="icon-container mb-3" style="font-size: 2.5rem; color: #03d476;">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="book-title">Notre Vision</h3>
                            <p>Devenir un centre d'excellence pour la préservation et la diffusion de la connaissance au Bénin, en étant à l'avant-garde des innovations technologiques.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Valeurs -->
        <section class="mb-5">
            <div class="section-title">
                <h2><i class="fas fa-star me-2"></i>Nos Valeurs</h2>
                <p>Les principes qui guident nos actions</p>
            </div>
            <div class="row">
                <div class="col-md-3 col-6 mb-4">
                    <div class="book-card h-100">
                        <div class="book-info text-center">
                            <div class="mb-3" style="font-size: 2rem; color: #03d476;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 class="book-title">Intégrité</h4>
                            <p class="book-author">Nous agissons avec honnêteté et transparence</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="book-card h-100">
                        <div class="book-info text-center">
                            <div class="mb-3" style="font-size: 2rem; color: #03d476;">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="book-title">Accessibilité</h4>
                            <p class="book-author">La connaissance accessible à tous sans discrimination</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="book-card h-100">
                        <div class="book-info text-center">
                            <div class="mb-3" style="font-size: 2rem; color: #03d476;">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h4 class="book-title">Innovation</h4>
                            <p class="book-author">Nous adoptons les nouvelles technologies</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="book-card h-100">
                        <div class="book-info text-center">
                            <div class="mb-3" style="font-size: 2rem; color: #03d476;">
                                <i class="fas fa-history"></i>
                            </div>
                            <h4 class="book-title">Préservation</h4>
                            <p class="book-author">Nous protégeons le patrimoine pour les générations futures</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Statistiques -->
        <section class="mb-5">
            <div class="section-title">
                <h2><i class="fas fa-chart-line me-2"></i>En Chiffres</h2>
                <p>L'impact de notre action à travers quelques statistiques</p>
            </div>
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4">
                    <div class="book-card h-100 py-4">
                        <div class="book-info">
                            <div class="stat-number" style="font-size: 2.5rem; color: #03d476; font-weight: 700;">250 000+</div>
                            <p class="book-author">Ouvrages</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="book-card h-100 py-4">
                        <div class="book-info">
                            <div class="stat-number" style="font-size: 2.5rem; color: #03d476; font-weight: 700;">15 000+</div>
                            <p class="book-author">Membres actifs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="book-card h-100 py-4">
                        <div class="book-info">
                            <div class="stat-number" style="font-size: 2.5rem; color: #03d476; font-weight: 700;">5 000+</div>
                            <p class="book-author">Manuscrits anciens</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="book-card h-100 py-4">
                        <div class="book-info">
                            <div class="stat-number" style="font-size: 2.5rem; color: #03d476; font-weight: 700;">45+</div>
                            <p class="book-author">Ans d'existence</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Équipe -->
        <section>
            <div class="section-title">
                <h2><i class="fas fa-users me-2"></i>Notre Équipe</h2>
                <p>Des professionnels dévoués à votre service</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="book-card h-100">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Directrice">
                        </div>
                        <div class="book-info text-center">
                            <h3 class="book-title">Marie Akpédjé</h3>
                            <p class="book-author">Directrice Générale</p>
                            <p class="book-category">À la tête de l'institution depuis 2018</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="book-card h-100">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Conservateur">
                        </div>
                        <div class="book-info text-center">
                            <h3 class="book-title">Jean Sègnon</h3>
                            <p class="book-author">Conservateur en chef</p>
                            <p class="book-category">Expert en préservation documentaire</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="book-card h-100">
                        <div class="book-image">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Responsable numérique">
                        </div>
                        <div class="book-info text-center">
                            <h3 class="book-title">Aïchatou Bello</h3>
                            <p class="book-author">Responsable du numérique</p>
                            <p class="book-category">Pilote notre transition numérique</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="contact.php" class="btn-custom btn-primary-custom">
                    <i class="fas fa-envelope me-2"></i> Nous contacter
                </a>
            </div>
        </section>
    </main>

    <!-- Pied de page -->
    <?php include_once "footer.php" ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>