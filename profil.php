<?php 
session_start();
include_once("includes/config.php");
include_once("auth_functions.php");

// Récupérer les informations de l'utilisateur connecté
$user_id = $_SESSION['id'];
$error_message = "";
$success_message = "";

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les statistiques de l'utilisateur
// Nombre d'emprunts
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM borrowings WHERE user_id = ?");
$stmt->execute([$user_id]);
$borrow_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Nombre de réservations en cours
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM reservations WHERE user_id = ? AND status IN ('Demande en cours...', 'Approuvée')");
$stmt->execute([$user_id]);
$reservation_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $address = $_POST['address'] ?? '';
    
    // Validation des données
    if (empty($name) || empty($first_name) || empty($email)) {
        $error_message = "Les champs nom, prénom et email sont obligatoires";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "L'adresse email n'est pas valide";
    } else {
        // Vérifier si l'email n'est pas déjà utilisé par un autre utilisateur
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing_user) {
            $error_message = "Cet email est déjà utilisé par un autre utilisateur";
        } else {
            // Mettre à jour les informations de l'utilisateur
            $stmt = $pdo->prepare("UPDATE users SET name = ?, first_name = ?, email = ?, phone = ?, birthdate = ?, address = ? WHERE id = ?");
            if ($stmt->execute([$name, $first_name, $email, $phone, $birthdate, $address, $user_id])) {
                $success_message = "Profil mis à jour avec succès";
                
                // Mettre à jour les informations en session si nécessaire
                $_SESSION['user_name'] = $first_name . ' ' . $name;
                $_SESSION['user_email'] = $email;
                
                // Recharger les informations de l'utilisateur
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error_message = "Une erreur s'est produite lors de la mise à jour";
            }
        }
    }
}

// Traitement du changement de mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "Tous les champs du mot de passe sont obligatoires";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "Les nouveaux mots de passe ne correspondent pas";
    } elseif (!password_verify($current_password, $user['pass_word'])) {
        $error_message = "Le mot de passe actuel est incorrect";
    } else {
        // Hasher le nouveau mot de passe
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Mettre à jour le mot de passe
        $stmt = $pdo->prepare("UPDATE users SET pass_word = ? WHERE id = ?");
        if ($stmt->execute([$hashed_password, $user_id])) {
            $success_message = "Mot de passe mis à jour avec succès";
        } else {
            $error_message = "Une erreur s'est produite lors de la mise à jour du mot de passe";
        }
    }
}

// Récupérer l'historique des emprunts
$stmt = $pdo->prepare("
    SELECT b.*, books.title, books.author, books.cover_image 
    FROM borrowings b 
    JOIN books ON b.book_id = books.id 
    WHERE b.user_id = ? 
    ORDER BY b.borrow_date DESC
    LIMIT 5
");
$stmt->execute([$user_id]);
$borrow_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les réservations en cours
$stmt = $pdo->prepare("
    SELECT r.*, books.title, books.author, books.cover_image 
    FROM reservations r 
    JOIN books ON r.book_id = books.id 
    WHERE r.user_id = ? AND r.status IN ('Demande en cours...', 'Approuvée')
    ORDER BY r.reservation_date DESC
");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Formater la date d'inscription
$join_date = date("F Y", strtotime($user['created_date']));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Bibliothèque</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Vos feuilles de style -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="css/profil.css">
    <style>
        
    </style>
</head>
<body>
    <!-- Barre de navigation -->
    <?php include_once "header.php"; ?>

    <div class="profile-container">
        <!-- En-tête du profil -->
        <div class="profile-header text-center">
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile" class="profile-img">
            <h2 class="mt-3"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['name']); ?></h2>
            <p class="mb-0">Membre depuis <?php echo $join_date; ?></p>
            
            <div class="profile-stats">
                <div class="stat">
                    <span class="stat-number"><?php echo $borrow_count; ?></span>
                    <span class="stat-label">Emprunts</span>
                </div>
                <div class="stat">
                    <span class="stat-number"><?php echo $reservation_count; ?></span>
                    <span class="stat-label">Réservations</span>
                </div>
            </div>
        </div>

        <!-- Affichage des messages -->
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <div class="row">
            <!-- Navigation latérale -->
            <div class="col-lg-3">
                <div class="profile-card">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#personal" data-bs-toggle="pill">
                                <i class="fas fa-user"></i> Informations personnelles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#history" data-bs-toggle="pill">
                                <i class="fas fa-history"></i> Historique des emprunts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#reservations" data-bs-toggle="pill">
                                <i class="fas fa-calendar-alt"></i> Réservations en cours
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="col-lg-9">
                <div class="tab-content">
                    <!-- Informations personnelles -->
                    <div class="tab-pane fade show active" id="personal">
                        <div class="profile-card">
                            <h4 class="card-title"><i class="fas fa-user-cog"></i> Gestion du profil</h4>
                            
                            <form method="POST" action="">
                                <input type="hidden" name="update_profile" value="1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Nom</label>
                                            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name">Prénom</label>
                                            <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Adresse email</label>
                                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Téléphone</label>
                                            <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="birthdate">Date de naissance</label>
                                            <input type="date" id="birthdate" name="birthdate" class="form-control" value="<?php echo htmlspecialchars($user['birthdate'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="npi">Numéro personnel d'identification (NPI)</label>
                                            <input type="text" id="npi" class="form-control" value="<?php echo htmlspecialchars($user['npi'] ?? ''); ?>" readonly>
                                            <small class="form-text text-muted">Le NPI ne peut pas être modifié</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="address">Adresse</label>
                                    <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            </form>
                            
                            <hr>
                            
                            <h5 class="mb-3">Changer le mot de passe</h5>
                            
                            <form method="POST" action="">
                                <input type="hidden" name="change_password" value="1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="current-password">Mot de passe actuel</label>
                                            <input type="password" id="current-password" name="current_password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="new-password">Nouveau mot de passe</label>
                                            <input type="password" id="new-password" name="new_password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="confirm-password">Confirmer le mot de passe</label>
                                            <input type="password" id="confirm-password" name="confirm_password" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                            </form>
                        </div>
                    </div>

                    <!-- Historique des emprunts -->
                    <div class="tab-pane fade" id="history">
                        <div class="profile-card">
                            <h4 class="card-title"><i class="fas fa-history"></i> Historique des emprunts</h4>
                            
                            <?php if (empty($borrow_history)): ?>
                                <p class="text-center">Aucun emprunt pour le moment.</p>
                            <?php else: ?>
                                <?php foreach ($borrow_history as $borrow): ?>
                                    <div class="history-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-1 col-2">
                                                <img src="<?php echo htmlspecialchars($borrow['cover_image']); ?>" class="book-cover" alt="<?php echo htmlspecialchars($borrow['title']); ?>">
                                            </div>
                                            <div class="col-md-7 col-10">
                                                <h5 class="mb-1"><?php echo htmlspecialchars($borrow['title']); ?></h5>
                                                <p class="mb-1 text-muted">Auteur: <?php echo htmlspecialchars($borrow['author']); ?></p>
                                                <p class="mb-0">Emprunté: <?php echo date('d/m/Y', strtotime($borrow['borrow_date'])); ?> - Retour: <?php echo date('d/m/Y', strtotime($borrow['due_date'])); ?></p>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <?php if ($borrow['status'] === 'retourné'): ?>
                                                    <span class="status returned">Retourné</span>
                                                <?php elseif ($borrow['status'] === 'en retard'): ?>
                                                    <span class="status late">En retard</span>
                                                <?php else: ?>
                                                    <span class="status borrowed">Emprunté</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <div class="text-center mt-4">
                                <a href="borrow_history.php" class="btn btn-outline-primary">Voir tout l'historique</a>
                            </div>
                        </div>
                    </div>

                    <!-- Réservations en cours -->
                    <div class="tab-pane fade" id="reservations">
                        <div class="profile-card">
                            <h4 class="card-title"><i class="fas fa-calendar-alt"></i> Réservations en cours</h4>
                            
                            <?php if (empty($reservations)): ?>
                                <p class="text-center">Aucune réservation en cours.</p>
                            <?php else: ?>
                                <?php foreach ($reservations as $reservation): ?>
                                    <div class="reservation-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-1 col-2">
                                                <img src="<?php echo htmlspecialchars($reservation['cover_image']); ?>" class="book-cover" alt="<?php echo htmlspecialchars($reservation['title']); ?>">
                                            </div>
                                            <div class="col-md-5 col-10">
                                                <h5 class="mb-1"><?php echo htmlspecialchars($reservation['title']); ?></h5>
                                                <p class="mb-1 text-muted">Auteur: <?php echo htmlspecialchars($reservation['author']); ?></p>
                                                <p class="mb-0">Réservé le: <?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <?php if ($reservation['status'] === 'Approuvée'): ?>
                                                    <span class="badge bg-success">Disponible</span>
                                                    <?php if ($reservation['pickup_deadline']): ?>
                                                        <p class="mb-0 small">À récupérer avant: <?php echo date('d/m/Y', strtotime($reservation['pickup_deadline'])); ?></p>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">En attente</span>
                                                    <?php if ($reservation['pickup_deadline']): ?>
                                                        <p class="mb-0 small">Dispo: <?php echo date('d/m/Y', strtotime($reservation['pickup_deadline'])); ?></p>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-3 text-md-end">
                                                <form method="POST" action="cancel_reservation.php" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation?');">
                                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Annuler</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <?php include_once "footer.php"; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>