<?php
session_start();
require_once 'includes/config.php';
require_once 'session_check.php';

// Vérifier si l'utilisateur est connecté et est administrateur
if (!isConnected() || !isAdmin()) {
    header("Location: login.php");
    exit();
}

// Récupérer les statistiques
try {
    // Nombre total de livres
    $stmt = $pdo->query("SELECT COUNT(*) as total_books FROM books WHERE status != 'DELETED'");
    $total_books = $stmt->fetch(PDO::FETCH_ASSOC)['total_books'];
    
    // Nombre total d'utilisateurs
    $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users WHERE status != 'DELETED'");
    $total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
    
    // Emprunts en cours
    $stmt = $pdo->query("SELECT COUNT(*) as current_borrowings FROM borrowings WHERE return_date IS NULL");
    $current_borrowings = $stmt->fetch(PDO::FETCH_ASSOC)['current_borrowings'];
    
    // Retards
    $stmt = $pdo->query("SELECT COUNT(*) as overdue_borrowings FROM borrowings WHERE return_date IS NULL AND due_date < NOW()");
    $overdue_borrowings = $stmt->fetch(PDO::FETCH_ASSOC)['overdue_borrowings'];
    
    // Récupérer les emprunts récents
    $stmt = $pdo->query("
        SELECT b.*, u.first_name, u.name, bk.title 
        FROM borrowings b 
        JOIN users u ON b.user_id = u.id 
        JOIN books bk ON b.book_id = bk.id 
        ORDER BY b.borrow_date DESC 
        LIMIT 10
    ");
    $recent_borrowings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer tous les livres
    $stmt = $pdo->query("SELECT * FROM books WHERE status != 'DELETED' ORDER BY title");
    $all_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer tous les utilisateurs
    $stmt = $pdo->query("SELECT id, name, first_name, email, phone, role, created_date FROM users WHERE status != 'DELETED' ORDER BY created_date DESC");
    $all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les emprunts par statut
    $stmt = $pdo->query("
        SELECT b.*, u.first_name, u.name, bk.title 
        FROM borrowings b 
        JOIN users u ON b.user_id = u.id 
        JOIN books bk ON b.book_id = bk.id 
        WHERE b.return_date IS NULL
    ");
    $current_borrows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $pdo->query("
        SELECT b.*, u.first_name, u.name, bk.title 
        FROM borrowings b 
        JOIN users u ON b.user_id = u.id 
        JOIN books bk ON b.book_id = bk.id 
        WHERE b.return_date IS NOT NULL
    ");
    $history_borrows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $pdo->query("
        SELECT b.*, u.first_name, u.name, bk.title 
        FROM borrowings b 
        JOIN users u ON b.user_id = u.id 
        JOIN books bk ON b.book_id = bk.id 
        WHERE b.return_date IS NULL AND b.due_date < NOW()
    ");
    $overdue_borrows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les réservations par statut
    $reservation_statuses = ['Demande en cours...', 'Approuvée', 'Emprunté', 'Rejetée'];
    $reservations_by_status = [];
    
    foreach ($reservation_statuses as $status) {
        $stmt = $pdo->prepare("
            SELECT r.*, u.first_name, u.name, b.title 
            FROM reservations r 
            JOIN users u ON r.user_id = u.id 
            JOIN books b ON r.book_id = b.id 
            WHERE r.status = ? 
            ORDER BY r.reservation_date DESC
        ");
        $stmt->execute([$status]);
        $reservations_by_status[$status] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des données: " . $e->getMessage();
}
// Récupérer les données pour les graphiques
try {
    // Évolution des inscriptions utilisateurs (30 derniers jours)
    $stmt = $pdo->query("
        SELECT DATE(created_date) as date, COUNT(*) as count 
        FROM users 
        WHERE created_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
        AND status != 'deleted'
        GROUP BY DATE(created_date) 
        ORDER BY date
    ");
    $user_registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Évolution des emprunts (30 derniers jours)
    $stmt = $pdo->query("
        SELECT DATE(borrow_date) as date, COUNT(*) as count 
        FROM borrowings 
        WHERE borrow_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
        GROUP BY DATE(borrow_date) 
        ORDER BY date
    ");
    $borrow_evolution = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Livres par catégorie
    $stmt = $pdo->query("
        SELECT category, COUNT(*) as count 
        FROM books 
        WHERE status != 'DELETED' 
        AND category IS NOT NULL
        GROUP BY category 
        ORDER BY count DESC
        LIMIT 10
    ");
    $books_by_category = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Statistiques mensuelles (6 derniers mois)
    $stmt = $pdo->query("
        SELECT 
            YEAR(created_date) as year,
            MONTH(created_date) as month,
            COUNT(*) as new_users,
            (SELECT COUNT(*) FROM borrowings WHERE YEAR(borrow_date) = year AND MONTH(borrow_date) = month) as borrowings,
            (SELECT COUNT(*) FROM reservations WHERE YEAR(reservation_date) = year AND MONTH(reservation_date) = month) as reservations
        FROM users 
        WHERE created_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        AND status != 'deleted'
        GROUP BY YEAR(created_date), MONTH(created_date)
        ORDER BY year, month
    ");
    $monthly_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des données graphiques: " . $e->getMessage();
    // Initialiser les tableaux vides pour éviter les erreurs
    $user_registrations = [];
    $borrow_evolution = [];
    $books_by_category = [];
    $monthly_stats = [];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Bibliothèque Nationale du Bénin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
     <!-- Chart.js -->
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <style>
     
    .charts-section {
    margin: 30px 0;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.chart-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.chart-card h4 {
    margin-bottom: 15px;
    color: #2c3e50;
    font-size: 16px;
    text-align: center;
}

.chart-card canvas {
    width: 100% !important;
    height: 250px !important;
}

/* Responsive pour mobile */
@media (max-width: 768px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .chart-card {
        padding: 15px;
    }
    
    .chart-card canvas {
        height: 200px !important;
    }
}
</style>  
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <div class="menu-toggle" id="menu-toggle">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-user-shield"></i>
            <h3>Administration</h3>
        </div>
        <nav class="sidebar-menu">
            <a href="#" class="menu-item active" data-tab="dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="#" class="menu-item" data-tab="books">
                <i class="fas fa-book"></i>
                <span>Gestion des livres</span>
            </a>
            <a href="#" class="menu-item" data-tab="users">
                <i class="fas fa-users"></i>
                <span>Gestion des utilisateurs</span>
            </a>
            <a href="#" class="menu-item" data-tab="borrowings">
                <i class="fas fa-exchange-alt"></i>
                <span>Gestion des emprunts</span>
            </a>
            <a href="#" class="menu-item" data-tab="reservations">
                <i class="fas fa-bookmark"></i>
                <span>Réservations</span>
            </a>
            <a href="logout.php" class="menu-item" id="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="admin-header">
            <h1>Administration - Bibliothèque Nationale du Bénin</h1>
            <div class="user-menu">
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['name']); ?></div>
                    <div class="user-role">Administrateur Principal</div>
                </div>
                <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['first_name'], 0, 1) . substr($_SESSION['name'], 0, 1)); ?></div>
                <div class="user-dropdown" id="user-dropdown">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        <span>Paramètres</span>
                    </a>
                    <a href="logout.php" class="dropdown-item" id="dropdown-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Dashboard Tab -->
        <section id="dashboard" class="tab-content active">
            <div class="dashboard">
                <div class="stat-card">
                    <div class="stat-icon books-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_books; ?></h3>
                        <p>Livres au total</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon users-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_users; ?></h3>
                        <p>Utilisateurs inscrits</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon borrowed-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $current_borrowings; ?></h3>
                        <p>Emprunts en cours</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon overdue-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $overdue_borrowings; ?></h3>
                        <p>Retards</p>
                    </div>
                </div>
            </div>
            
           <!-- Section Graphiques -->
            
<div class="charts-section">
    <div class="section-header">
        <h2>Statistiques et Évolution</h2>
    </div>
    
    <div class="charts-grid">
        <!-- Graphique 1: Évolution des inscriptions -->
        <div class="chart-card">
            <h4>Évolution des inscriptions (30 jours)</h4>
            <canvas id="userRegistrationsChart"></canvas>
        </div>
        
        <!-- Graphique 2: Évolution des emprunts -->
        <div class="chart-card">
            <h4>Évolution des emprunts (30 jours)</h4>
            <canvas id="borrowEvolutionChart"></canvas>
        </div>
        
        <!-- Graphique 3: Livres par catégorie -->
        <div class="chart-card">
            <h4>Répartition par catégorie</h4>
            <canvas id="booksByCategoryChart"></canvas>
        </div>
        
        <!-- Graphique 4: Statistiques mensuelles -->
        <div class="chart-card">
            <h4>Statistiques mensuelles</h4>
            <canvas id="monthlyStatsChart"></canvas>
        </div>
    </div>
</div>
            <div class="content-section">
                <div class="section-header">
                    <h2>Emprunts récents</h2>
                    <div class="section-actions">
                        <button class="btn btn-primary" id="add-book-from-dashboard">
                            <i class="fas fa-plus"></i> Ajouter un livre
                        </button>
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Livre</th>
                                <th>Emprunteur</th>
                                <th>Date d'emprunt</th>
                                <th>Date de retour</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_borrowings as $borrowing): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($borrowing['title']); ?></td>
                                <td><?php echo htmlspecialchars($borrowing['first_name'] . ' ' . $borrowing['name']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($borrowing['borrow_date'])); ?></td>
                                <td><?php echo $borrowing['return_date'] ? date('d/m/Y', strtotime($borrowing['return_date'])) : date('d/m/Y', strtotime($borrowing['due_date'])); ?></td>
                                <td>
                                    <span class="status status-<?php 
                                        if ($borrowing['return_date']) echo 'available';
                                        elseif (strtotime($borrowing['due_date']) < time()) echo 'overdue';
                                        else echo 'borrowed';
                                    ?>">
                                        <?php 
                                        if ($borrowing['return_date']) echo 'Retourné';
                                        elseif (strtotime($borrowing['due_date']) < time()) echo 'En retard';
                                        else echo 'Emprunté';
                                        ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <button class="btn btn-danger btn-sm delete-borrowing" data-id="<?php echo $borrowing['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Books Management Tab -->
        <section id="books" class="tab-content">
            <div class="content-section">
                <div class="section-header">
                    <h2>Gestion des livres</h2>
                    <div class="section-actions">
                        <button class="btn btn-primary" id="add-book-btn">
                            <i class="fas fa-plus"></i> Ajouter un livre
                        </button>
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Catégorie</th>
                                <th>ISBN</th>
                                <th>Disponibilité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_books as $book): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo htmlspecialchars($book['category']); ?></td>
                                <td><?php echo htmlspecialchars($book['isbn'] ?? '-'); ?></td>
                                <td>
                                    <span class="status status-<?php echo $book['status'] == 'DISPONIBLE' ? 'available' : 'borrowed'; ?>">
                                        <?php echo $book['status'] == 'DISPONIBLE' ? 'Disponible' : 'Indisponible'; ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <button class="btn btn-danger btn-sm delete-book" data-id="<?php echo $book['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Gestion des utilisateurs Tab -->
        <section id="users" class="tab-content">
            <div class="content-section">
                <div class="section-header">
                    <h2>Gestion des utilisateurs</h2>
                    <div class="section-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <button class="btn btn-primary" id="add-user-btn">
                            <i class="fas fa-plus"></i> Ajouter un utilisateur
                        </button>
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Rôle</th>
                                <th>Date d'inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                                <td>
                                    <span class="status status-<?php echo $user['role'] == 'admin' ? 'available' : 'borrowed'; ?>">
                                        <?php echo $user['role'] == 'admin' ? 'Admin' : 'Utilisateur'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($user['created_date'])); ?></td>
                                <td class="action-buttons">
                                    <button class="btn btn-danger btn-sm delete-user" data-id="<?php echo $user['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Gestion des emprunts Tab -->
        <section id="borrowings" class="tab-content">
            <div class="content-section">
                <div class="section-header">
                    <h2>Gestion des emprunts</h2>
                    <div class="section-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <button class="btn btn-primary" id="add-borrowing-btn">
                            <i class="fas fa-plus"></i> Nouvel emprunt
                        </button>
                    </div>
                </div>
                <div class="tabs">
                    <div class="tab active" data-borrowing-tab="current">Emprunts en cours</div>
                    <div class="tab" data-borrowing-tab="history">Historique</div>
                    <div class="tab" data-borrowing-tab="overdue">Retards</div>
                </div>
                
                <!-- Emprunts en cours -->
                <div class="tab-content active" id="current-borrowings">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Livre</th>
                                    <th>Emprunteur</th>
                                    <th>Date d'emprunt</th>
                                    <th>Date de retour prévue</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($current_borrows as $borrow): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($borrow['title']); ?></td>
                                    <td><?php echo htmlspecialchars($borrow['first_name'] . ' ' . $borrow['name']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($borrow['borrow_date'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($borrow['due_date'])); ?></td>
                                    <td>
                                        <span class="status status-<?php 
                                            if (strtotime($borrow['due_date']) < time()) echo 'overdue';
                                            else echo 'borrowed';
                                        ?>">
                                            <?php 
                                            if (strtotime($borrow['due_date']) < time()) echo 'En retard';
                                            else echo 'Emprunté';
                                            ?>
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn btn-success btn-sm return-book" data-id="<?php echo $borrow['id']; ?>">
                                            <i class="fas fa-check"></i> Retour
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Historique des emprunts -->
                <div class="tab-content" id="history-borrowings">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Livre</th>
                                    <th>Emprunteur</th>
                                    <th>Date d'emprunt</th>
                                    <th>Date de retour</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history_borrows as $borrow): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($borrow['title']); ?></td>
                                    <td><?php echo htmlspecialchars($borrow['first_name'] . ' ' . $borrow['name']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($borrow['borrow_date'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($borrow['return_date'])); ?></td>
                                    <td><span class="status status-available">Retourné</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Retards -->
                <div class="tab-content" id="overdue-borrowings">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Livre</th>
                                    <th>Emprunteur</th>
                                    <th>Date d'emprunt</th>
                                    <th>Date de retour prévue</th>
                                    <th>Jours de retard</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($overdue_borrows as $borrow): 
                                    $days_late = floor((time() - strtotime($borrow['due_date'])) / (60 * 60 * 24));
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($borrow['title']); ?></td>
                                    <td><?php echo htmlspecialchars($borrow['first_name'] . ' ' . $borrow['name']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($borrow['borrow_date'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($borrow['due_date'])); ?></td>
                                    <td><span class="status status-overdue"><?php echo $days_late; ?> jours</span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-success btn-sm return-book" data-id="<?php echo $borrow['id']; ?>">
                                            <i class="fas fa-check"></i> Retour
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Réservations Tab -->
        <section id="reservations" class="tab-content">
            <div class="content-section">
                <div class="section-header">
                    <h2>Gestion des réservations</h2>
                    <div class="section-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                    </div>
                </div>
                <div class="tabs">
                    <div class="tab active" data-reservation-tab="pending">En attente</div>
                    <div class="tab" data-reservation-tab="active">Actives</div>
                    <div class="tab" data-reservation-tab="completed">Traitées</div>
                    <div class="tab" data-reservation-tab="cancelled">Annulées</div>
                </div>
                
                <!-- Réservations en attente -->
                <div class="tab-content active" id="pending-reservations">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Livre</th>
                                    <th>Utilisateur</th>
                                    <th>Date de réservation</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations_by_status['Demande en cours...'] as $reservation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reservation['title']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['name']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                                    <td><span class="status status-pending"><?php echo $reservation['status']; ?></span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-success btn-sm approve-reservation" data-id="<?php echo $reservation['id']; ?>">
                                            <i class="fas fa-check"></i> Approuver
                                        </button>
                                        <button class="btn btn-danger btn-sm reject-reservation" data-id="<?php echo $reservation['id']; ?>">
                                            <i class="fas fa-times"></i> Rejeter
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Réservations actives -->
                <div class="tab-content" id="active-reservations">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Livre</th>
                                    <th>Utilisateur</th>
                                    <th>Date de réservation</th>
                                    <th>Date d'expiration</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations_by_status['Approuvée'] as $reservation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reservation['title']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['name']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                                    <td><?php echo $reservation['pickup_deadline'] ? date('d/m/Y', strtotime($reservation['pickup_deadline'])) : '-'; ?></td>
                                    <td><span class="status status-available"><?php echo $reservation['status']; ?></span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-primary btn-sm convert-reservation" data-id="<?php echo $reservation['id']; ?>">
                                            <i class="fas fa-exchange-alt"></i> Convertir
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Réservations traitées -->
                <div class="tab-content" id="completed-reservations">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Livre</th>
                                    <th>Utilisateur</th>
                                    <th>Date de réservation</th>
                                    <th>Date de traitement</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations_by_status['Emprunté'] as $reservation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reservation['title']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['name']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                                    <td><?php echo $reservation['approved_at'] ? date('d/m/Y', strtotime($reservation['approved_at'])) : '-'; ?></td>
                                    <td><span class="status status-borrowed"><?php echo $reservation['status']; ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Réservations annulées -->
                <div class="tab-content" id="cancelled-reservations">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Livre</th>
                                    <th>Utilisateur</th>
                                    <th>Date de réservation</th>
                                    <th>Date d'annulation</th>
                                    <th>Raison</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations_by_status['Rejetée'] as $reservation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reservation['title']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['name']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                                    <td><?php echo $reservation['approved_at'] ? date('d/m/Y', strtotime($reservation['approved_at'])) : '-'; ?></td>
                                    <td>Annulation administrateur</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Add Book Modal -->
        <div class="modal" id="add-book-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Ajouter un nouveau livre</h3>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="add-book-form" method="POST" action="process_books.php" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_book">
                        <input type="hidden" name="type" value="physical">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="book-title">Titre du livre</label>
                                <input type="text" id="book-title" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="book-author">Auteur</label>
                                <input type="text" id="book-author" name="author" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="book-isbn">ISBN</label>
                                <input type="text" id="book-isbn" name="isbn" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="book-category">Catégorie</label>
                                <select id="book-category" name="category" class="form-control" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <option value="fiction">Fiction</option>
                                    <option value="history">Histoire</option>
                                    <option value="science">Science</option>
                                    <option value="art">Art</option>
                                    <option value="philosophy">Philosophie</option>
                                    <option value="literature">Littérature</option>
                                    <option value="nature">Nature</option>
                                    <option value="music">Musique</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="book-publisher">Éditeur</label>
                                <input type="text" id="book-publisher" name="publisher" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="book-year">Année de publication</label>
                                <input type="number" id="book-year" name="publication_date" class="form-control" min="1900" max="<?php echo date('Y'); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="book-description">Description</label>
                            <textarea id="book-description" name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="book-cover">Couverture du livre</label>
                            <input type="file" id="book-cover" name="cover_image" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="book-status">Statut</label>
                            <select id="book-status" name="status" class="form-control" required>
                                <option value="DISPONIBLE">Disponible</option>
                                <option value="INDISPONIBLE">Indisponible</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline" id="cancel-add-book">Annuler</button>
                    <button class="btn btn-primary" id="submit-add-book">Ajouter le livre</button>
                </div>
            </div>
        </div>

        <!-- Toast Notification -->
        <div class="toast" id="toast">
            <i class="fas fa-check-circle"></i>
            <span id="toast-message">Opération réussie</span>
        </div>
    </main>

    <script>
        // Tab navigation
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (this.id === 'logout-btn') {
                    if (confirm('Êtes-vous sûr de vouloir vous déconnecter?')) {
                        showToast('Déconnexion réussie');
                        setTimeout(() => {
                            window.location.href = 'logout.php';
                        }, 1500);
                    }
                    return;
                }
                
                // Remove active class from all items
                document.querySelectorAll('.menu-item').forEach(i => {
                    i.classList.remove('active');
                });
                
                // Add active class to clicked item
                this.classList.add('active');
                
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Show the selected tab content
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
                
                // Close sidebar on mobile after selection
                if (window.innerWidth < 1200) {
                    document.getElementById('sidebar').classList.remove('show');
                }
            });
        });

        // User dropdown menu
        const userAvatar = document.querySelector('.user-avatar');
        const userDropdown = document.getElementById('user-dropdown');
        
        userAvatar.addEventListener('click', function() {
            userDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userAvatar.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('show');
            }
        });

        // Dropdown menu items
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (this.id === 'dropdown-logout') {
                    if (confirm('Êtes-vous sûr de vouloir vous déconnecter?')) {
                        showToast('Déconnexion réussie');
                        setTimeout(() => {
                            window.location.href = 'logout.php';
                        }, 1500);
                    }
                    return;
                }
                
                // Close dropdown
                userDropdown.classList.remove('show');
            });
        });

        // Modal functionality
        const addBookBtn = document.getElementById('add-book-btn');
        const addBookModal = document.getElementById('add-book-modal');
        const cancelAddBook = document.getElementById('cancel-add-book');
        const modalClose = document.querySelector('.modal-close');
        const addBookFromDashboard = document.getElementById('add-book-from-dashboard');

        if (addBookBtn) {
            addBookBtn.addEventListener('click', () => {
                addBookModal.style.display = 'flex';
            });
        }

        if (addBookFromDashboard) {
            addBookFromDashboard.addEventListener('click', () => {
                addBookModal.style.display = 'flex';
            });
        }

        if (cancelAddBook) {
            cancelAddBook.addEventListener('click', () => {
                addBookModal.style.display = 'none';
            });
        }

        if (modalClose) {
            modalClose.addEventListener('click', () => {
                addBookModal.style.display = 'none';
            });
        }

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === addBookModal) {
                addBookModal.style.display = 'none';
            }
        });

        // Form submission
        document.getElementById('submit-add-book').addEventListener('click', () => {
            document.getElementById('add-book-form').submit();
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            toast.className = 'toast';
            
            if (type === 'error') {
                toast.classList.add('error');
                toast.querySelector('i').className = 'fas fa-exclamation-circle';
            } else if (type === 'warning') {
                toast.classList.add('warning');
                toast.querySelector('i').className = 'fas fa-exclamation-triangle';
            } else {
                toast.querySelector('i').className = 'fas fa-check-circle';
            }
            
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Gestion des onglets dans les sections emprunts et réservations
        document.querySelectorAll('[data-borrowing-tab]').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabName = this.getAttribute('data-borrowing-tab');
                
                // Remove active class from all tabs
                document.querySelectorAll('[data-borrowing-tab]').forEach(t => {
                    t.classList.remove('active');
                });
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Hide all borrowing tab contents
                document.querySelectorAll('#borrowings .tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Show the selected tab content
                document.getElementById(tabName + '-borrowings').classList.add('active');
            });
        });

        document.querySelectorAll('[data-reservation-tab]').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabName = this.getAttribute('data-reservation-tab');
                
                // Remove active class from all tabs
                document.querySelectorAll('[data-reservation-tab]').forEach(t => {
                    t.classList.remove('active');
                });
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Hide all reservation tab contents
                document.querySelectorAll('#reservations .tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Show the selected tab content
                document.getElementById(tabName + '-reservations').classList.add('active');
            });
        });

        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menu-toggle');
            
            if (window.innerWidth < 1200 && 
                !sidebar.contains(e.target) && 
                !menuToggle.contains(e.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Gestion des actions (suppression, approbation, etc.)
        document.querySelectorAll('.delete-book').forEach(button => {
            button.addEventListener('click', function() {
                const bookId = this.getAttribute('data-id');
                if (confirm('Êtes-vous sûr de vouloir supprimer ce livre?')) {
                    fetch('manage_data.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'delete_book',
                            book_id: bookId,
                            book_type: 'physical'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            this.closest('tr').remove();
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showToast('Erreur lors de la suppression', 'error');
                    });
                }
            });
        });

        document.querySelectorAll('.delete-user').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')) {
                    fetch('manage_data.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'delete_user',
                            user_id: userId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            this.closest('tr').remove();
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showToast('Erreur lors de la suppression', 'error');
                    });
                }
            });
        });

        document.querySelectorAll('.return-book').forEach(button => {
            button.addEventListener('click', function() {
                const borrowingId = this.getAttribute('data-id');
                if (confirm('Marquer ce livre comme retourné?')) {
                    fetch('manage_data.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'return_book',
                            borrowing_id: borrowingId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            this.closest('tr').remove();
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showToast('Erreur lors du retour', 'error');
                    });
                }
            });
        });

        document.querySelectorAll('.approve-reservation').forEach(button => {
            button.addEventListener('click', function() {
                const reservationId = this.getAttribute('data-id');
                if (confirm('Approuver cette réservation?')) {
                    fetch('manage_data.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'approve_reservation',
                            reservation_id: reservationId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            this.closest('tr').remove();
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showToast('Erreur lors de l\'approbation', 'error');
                    });
                }
            });
        });

        document.querySelectorAll('.reject-reservation').forEach(button => {
            button.addEventListener('click', function() {
                const reservationId = this.getAttribute('data-id');
                if (confirm('Rejeter cette réservation?')) {
                    fetch('manage_data.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'reject_reservation',
                            reservation_id: reservationId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            this.closest('tr').remove();
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showToast('Erreur lors du rejet', 'error');
                    });
                }
            });
        });

        document.querySelectorAll('.convert-reservation').forEach(button => {
            button.addEventListener('click', function() {
                const reservationId = this.getAttribute('data-id');
                if (confirm('Convertir cette réservation en emprunt?')) {
                    fetch('manage_data.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'convert_reservation',
                            reservation_id: reservationId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            this.closest('tr').remove();
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showToast('Erreur lors de la conversion', 'error');
                    });
                }
            });
        });

        // Charger les données au chargement de la page
        document.addEventListener('DOMContentLoaded', () => {
            showToast('Bienvenue dans l\'administration!');
        });
    </script>
    <script>
        // Fonctions utilitaires pour les graphiques
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' });
}

function getMonthName(monthNumber) {
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    return months[monthNumber - 1] || '';
}

// Initialisation des graphiques
function initCharts() {
    // Graphique: Évolution des inscriptions
    const userRegistrationsCtx = document.getElementById('userRegistrationsChart');
    if (userRegistrationsCtx) {
        const labels = <?php echo json_encode(array_map(function($item) { 
            return formatDate($item['date']); 
        }, $user_registrations)); ?>;
        
        const data = <?php echo json_encode(array_column($user_registrations, 'count')); ?>;
        
        new Chart(userRegistrationsCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Inscriptions',
                    data: data,
                    borderColor: '#03d476',
                    backgroundColor: 'rgba(3, 212, 118, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Graphique: Évolution des emprunts
    const borrowEvolutionCtx = document.getElementById('borrowEvolutionChart');
    if (borrowEvolutionCtx) {
        const labels = <?php echo json_encode(array_map(function($item) { 
            return formatDate($item['date']); 
        }, $borrow_evolution)); ?>;
        
        const data = <?php echo json_encode(array_column($borrow_evolution, 'count')); ?>;
        
        new Chart(borrowEvolutionCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Emprunts',
                    data: data,
                    backgroundColor: '#3498db',
                    borderColor: '#2980b9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Graphique: Livres par catégorie
    const booksByCategoryCtx = document.getElementById('booksByCategoryChart');
    if (booksByCategoryCtx) {
        const labels = <?php echo json_encode(array_column($books_by_category, 'category')); ?>;
        const data = <?php echo json_encode(array_column($books_by_category, 'count')); ?>;
        
        new Chart(booksByCategoryCtx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#03d476', '#3498db', '#e74c3c', '#f39c12', 
                        '#9b59b6', '#1abc9c', '#34495e', '#d35400',
                        '#7f8c8d', '#27ae60'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    }

    // Graphique: Statistiques mensuelles
    const monthlyStatsCtx = document.getElementById('monthlyStatsChart');
    if (monthlyStatsCtx) {
        const labels = <?php echo json_encode(array_map(function($item) { 
            return getMonthName($item['month']) + ' ' + $item['year']; 
        }, $monthly_stats)); ?>;
        
        const newUsersData = <?php echo json_encode(array_column($monthly_stats, 'new_users')); ?>;
        const borrowingsData = <?php echo json_encode(array_column($monthly_stats, 'borrowings')); ?>;
        const reservationsData = <?php echo json_encode(array_column($monthly_stats, 'reservations')); ?>;
        
        new Chart(monthlyStatsCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Nouveaux utilisateurs',
                        data: newUsersData,
                        backgroundColor: '#03d476'
                    },
                    {
                        label: 'Emprunts',
                        data: borrowingsData,
                        backgroundColor: '#3498db'
                    },
                    {
                        label: 'Réservations',
                        data: reservationsData,
                        backgroundColor: '#f39c12'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
}

// Fonction pour formater les dates en français
function formatDate(dateString) {
    const date = new Date(dateString + 'T00:00:00');
    return date.toLocaleDateString('fr-FR', { 
        day: '2-digit', 
        month: '2-digit' 
    });
}

// Fonction pour obtenir le nom du mois
function getMonthName(monthNumber) {
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    return months[monthNumber - 1] || 'M' + monthNumber;
}

// Initialiser les graphiques au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    initCharts();
    showToast('Bienvenue dans l\'administration!');
});
    </script>
    
</body>
</html>