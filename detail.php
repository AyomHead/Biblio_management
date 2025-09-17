<?php
session_start();
include_once("includes/config.php");

// Vérifier si un ID de livre est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: catalogue.php");
    exit();
}

// Récupérer et valider l'ID du livre
$book_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($book_id === false || $book_id <= 0) {
    header("Location: catalogue.php");
    exit();
}

// Récupérer les informations du livre
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si le livre existe
if (!$book) {
    header("Location: catalogue.php");
    exit();
}

// Traitement de la réservation
$reservation_message = "";
$reservation_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve_book'])) {
    // Vérifier si l'utilisateur est connecté (optionnel, selon votre demande)
    // if (!isset($_SESSION['id'])) {
    //     $reservation_message = "Vous devez être connecté pour réserver un livre";
    // } else {
        $user_id = $_SESSION['id'] ?? null; // Récupérer l'ID utilisateur si disponible
        
        // Vérifier si l'utilisateur a déjà réservé ce livre avec un statut actif
        $stmt = $pdo->prepare("SELECT id FROM reservations WHERE user_id = ? AND book_id = ? AND status IN ('Demande en cours...', 'Approuvée', 'Emprunté')");
        $stmt->execute([$user_id, $book_id]);
        $existing_reservation = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing_reservation) {
            $reservation_message = "Vous avez déjà une réservation active pour ce livre";
        } else {
            try {
                // Commencer une transaction pour garantir l'intégrité des données
                $pdo->beginTransaction();
                
                // Créer la réservation avec le statut "Demande en cours..."
                $stmt = $pdo->prepare("INSERT INTO reservations (user_id, book_id, reservation_date, status) VALUES (?, ?, NOW(), 'Demande en cours...')");
                $stmt->execute([$user_id, $book_id]);
                
                // Mettre à jour le statut du livre si nécessaire
                if ($book['status'] === 'DISPONIBLE') {
                    $stmt = $pdo->prepare("UPDATE books SET status = 'INDISPONIBLE' WHERE id = ?");
                    $stmt->execute([$book_id]);
                    $book['status'] = 'INDISPONIBLE';
                }
                
                // Valider la transaction
                $pdo->commit();
                
                $reservation_message = "Votre réservation a été enregistrée avec succès et est en attente de confirmation par un administrateur";
                $reservation_success = true;
                
            } catch (Exception $e) {
                // En cas d'erreur, annuler la transaction
                $pdo->rollBack();
                $reservation_message = "Une erreur s'est produite lors de la réservation: " . $e->getMessage();
            }
        }
    // }
}

// Formater la date de publication si elle existe
$publication_date = '';
if (!empty($book['publication_date'])) {
    $publication_date = date('Y', strtotime($book['publication_date']));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - Bibliothèque Nationale du Bénin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Style principal -->
    <link rel="stylesheet" href="css/Style.css">
    <style>
        .book-detail-container {
            background: rgba(26, 70, 87, 0.3);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            padding: 30px;
            margin-top: 20px;
        }
        
        .book-detail-image {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .book-detail-title {
            color: #03d476;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .book-detail-info {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .book-detail-info th {
            color: #03d476;
            width: 30%;
        }
        
        .book-detail-info td {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .btn-retour {
            background: #03d476;
            border: none;
            color: #fff;
            transition: all 0.3s ease;
        }
        
        .btn-retour:hover {
            background: #fff;
            color: #013244;
        }
        
        .btn-reserve {
            background: #03d476;
            border: none;
            color: #fff;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-reserve:hover {
            background: #fff;
            color: #013244;
        }
        
        .btn-reserve:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        
        .book-description {
            color: #fff !important;
        }
        
        .alert-reservation {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Barre de navigation -->
    <?php include_once "header.php"; ?>

    <!-- Contenu principal -->
    <main class="container py-4">
        <!-- Bouton Retour -->
        <div class="mb-4">
            <a href="catalogue.php" class="btn btn-retour rounded-pill">
                <i class="fas fa-arrow-left me-2"></i>Retour au catalogue
            </a>
        </div>
        
        <h1 class="text-center mb-4 book-detail-title">
            <i class="fas fa-book me-2"></i>Détails du Livre
        </h1>
        <p class="text-center mb-5 book-description">Informations complètes sur l'ouvrage sélectionné</p>
        
        <div class="book-detail-container">
            <!-- Message de réservation -->
            <?php if (!empty($reservation_message)): ?>
                <div class="alert <?php echo $reservation_success ? 'alert-success' : 'alert-danger'; ?> alert-reservation">
                    <?php echo $reservation_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-5 text-center">
                    <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" 
                         alt="Couverture de <?php echo htmlspecialchars($book['title']); ?>" class="book-detail-image">
                </div>
                
                <div class="col-lg-8 col-md-7">
                    <table class="table book-detail-info">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center h4 py-3">Détails du livre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Titre</th>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                            </tr>
                            <tr>
                                <th>Auteur</th>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                            </tr>
                            <tr>
                                <th>Catégorie</th>
                                <td><?php echo htmlspecialchars($book['category']); ?></td>
                            </tr>
                            <?php if (!empty($book['isbn'])): ?>
                            <tr>
                                <th>ISBN</th>
                                <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($publication_date)): ?>
                            <tr>
                                <th>Date de publication</th>
                                <td><?php echo $publication_date; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($book['publisher'])): ?>
                            <tr>
                                <th>Éditeur</th>
                                <td><?php echo htmlspecialchars($book['publisher']); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Statut</th>
                                <td>
                                    <?php if ($book['status'] === 'DISPONIBLE'): ?>
                                        <span class="badge bg-success">Disponible</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Indisponible</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <?php if (!empty($book['description'])): ?>
                    <h4 class="mt-4 mb-3" style="color: #03d476;">Résumé :</h4>
                    <p class="book-detail-info">
                        <?php echo htmlspecialchars($book['description']); ?>
                    </p>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-start mt-4">
                        <form method="POST" action="">
                            <button type="submit" name="reserve_book" class="btn btn-reserve" 
                                <?php if ($book['status'] !== 'DISPONIBLE') echo 'disabled'; ?>>
                                <i class="fas fa-bookmark me-2"></i>
                                <?php 
                                if ($book['status'] !== 'DISPONIBLE') {
                                    echo "Indisponible";
                                } else {
                                    echo "Réserver ce livre";
                                }
                                ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>   
        </div>
    </main>

    <!-- Pied de page -->
    <?php include_once "footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>