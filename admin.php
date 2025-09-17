<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Bibliothèque Nationale du Bénin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
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
                    <div class="user-name">Marie Kodjo</div>
                    <div class="user-role">Administrateur Principal</div>
                </div>
                <div class="user-avatar">MK</div>
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
                        <h3>2,548</h3>
                        <p>Livres au total</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon users-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>1,237</h3>
                        <p>Utilisateurs inscrits</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon borrowed-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>384</h3>
                        <p>Emprunts en cours</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon overdue-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>27</h3>
                        <p>Retards</p>
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
                            <tr>
                                <td>L'Enfant Noir</td>
                                <td>Koffi Mensah</td>
                                <td>12/08/2023</td>
                                <td>26/08/2023</td>
                                <td><span class="status status-borrowed">Emprunté</span></td>
                                <td class="action-buttons">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Une si longue lettre</td>
                                <td>Aïcha Diallo</td>
                                <td>10/08/2023</td>
                                <td>24/08/2023</td>
                                <td><span class="status status-overdue">En retard</span></td>
                                <td class="action-buttons">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>L'Aventure ambiguë</td>
                                <td>Jean Dupont</td>
                                <td>15/08/2023</td>
                                <td>29/08/2023</td>
                                <td><span class="status status-borrowed">Emprunté</span></td>
                                <td class="action-buttons">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
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
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>L'Enfant Noir</td>
                                <td>Camara Laye</td>
                                <td>Littérature</td>
                                <td>978-2-253-01115-9</td>
                                <td><span class="status status-available">Disponible</span></td>
                                <td class="action-buttons">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Une si longue lettre</td>
                                <td>Mariama Bâ</td>
                                <td>Roman</td>
                                <td>978-2-7096-0549-5</td>
                                <td><span class="status status-borrowed">Emprunté</span></td>
                                <td class="action-buttons">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
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
                                <th>Statut</th>
                                <th>Date d'inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Koffi Mensah</td>
                                <td>koffi.mensah@email.com</td>
                                <td>+229 97 85 63 21</td>
                                <td><span class="status status-available">Actif</span></td>
                                <td>15/06/2023</td>
                                <td class="action-buttons">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Aïcha Diallo</td>
                                <td>aicha.diallo@email.com</td>
                                <td>+229 65 41 78 95</td>
                                <td><span class="status status-borrowed">Inactif</span></td>
                                <td>22/07/2023</td>
                                <td class="action-buttons">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
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
                                <tr>
                                    <td>L'Enfant Noir</td>
                                    <td>Koffi Mensah</td>
                                    <td>12/08/2023</td>
                                    <td>26/08/2023</td>
                                    <td><span class="status status-borrowed">Emprunté</span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Une si longue lettre</td>
                                    <td>Aïcha Diallo</td>
                                    <td>10/08/2023</td>
                                    <td>24/08/2023</td>
                                    <td><span class="status status-overdue">En retard</span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>L'Aventure ambiguë</td>
                                    <td>Jean Dupont</td>
                                    <td>01/08/2023</td>
                                    <td>15/08/2023</td>
                                    <td><span class="status status-available">Retourné</span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sous l'orage</td>
                                    <td>Fatou Diop</td>
                                    <td>05/07/2023</td>
                                    <td>19/07/2023</td>
                                    <td><span class="status status-available">Retourné</span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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
                                <tr>
                                    <td>Une si longue lettre</td>
                                    <td>Aïcha Diallo</td>
                                    <td>10/08/2023</td>
                                    <td>24/08/2023</td>
                                    <td><span class="status status-overdue">3 jours</span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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
                                <tr>
                                    <td>L'Enfant Noir</td>
                                    <td>Koffi Mensah</td>
                                    <td>20/08/2023</td>
                                    <td><span class="status status-pending">En attente</span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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
                                <tr>
                                    <td>L'Aventure ambiguë</td>
                                    <td>Jean Dupont</td>
                                    <td>18/08/2023</td>
                                    <td>25/08/2023</td>
                                    <td><span class="status status-available">Active</span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sous l'orage</td>
                                    <td>Fatou Diop</td>
                                    <td>10/08/2023</td>
                                    <td>15/08/2023</td>
                                    <td><span class="status status-borrowed">Convertie</span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Le Monde s'effondre</td>
                                    <td>Paul Agbodjan</td>
                                    <td>05/08/2023</td>
                                    <td>08/08/2023</td>
                                    <td>Non récupérée</td>
                                    <td class="action-buttons">
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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
                    <form id="add-book-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="book-title">Titre du livre</label>
                                <input type="text" id="book-title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="book-author">Auteur</label>
                                <input type="text" id="book-author" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="book-isbn">ISBN</label>
                                <input type="text" id="book-isbn" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="book-category">Catégorie</label>
                                <select id="book-category" class="form-control" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <option value="fiction">Fiction</option>
                                    <option value="history">Histoire</option>
                                    <option value="science">Science</option>
                                    <option value="art">Art</option>
                                    <option value="philosophy">Philosophie</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="book-publisher">Éditeur</label>
                                <input type="text" id="book-publisher" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="book-year">Année de publication</label>
                                <input type="number" id="book-year" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="book-description">Description</label>
                            <textarea id="book-description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="book-cover">Couverture du livre</label>
                            <input type="file" id="book-cover" class="form-control">
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
            // Basic form validation
            const title = document.getElementById('book-title').value;
            const author = document.getElementById('book-author').value;
            const isbn = document.getElementById('book-isbn').value;
            
            if (title && author && isbn) {
                showToast('Livre ajouté avec succès!');
                addBookModal.style.display = 'none';
                document.getElementById('add-book-form').reset();
            } else {
                showToast('Veuillez remplir tous les champs obligatoires', 'error');
            }
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

        // Simulated data loading
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Admin dashboard loaded');
            showToast('Bienvenue, Marie!');
        });
        
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
    </script>

    <script>
        // Charger les données depuis le backend
        function loadData() {
            // Charger les statistiques
            fetch('get_data.php?type=stats')
                .then(response => response.json())
                .then(stats => {
                    document.querySelectorAll('.stat-card')[0].querySelector('h3').textContent = stats.total_books;
                    document.querySelectorAll('.stat-card')[1].querySelector('h3').textContent = stats.total_users;
                    document.querySelectorAll('.stat-card')[2].querySelector('h3').textContent = stats.current_borrowings;
                    document.querySelectorAll('.stat-card')[3].querySelector('h3').textContent = stats.overdue_borrowings;
                })
                .catch(error => console.error('Erreur lors du chargement des statistiques:', error));
            
            // Charger les livres
            fetch('get_data.php?type=books')
                .then(response => response.json())
                .then(books => {
                    const tbody = document.querySelector('#books table tbody');
                    tbody.innerHTML = '';
                    
                    books.forEach(book => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${book.title}</td>
                            <td>${book.author}</td>
                            <td>${book.category}</td>
                            <td>${book.isbn || '-'}</td>
                            <td><span class="status status-${book.status === 'DISPONIBLE' ? 'available' : 'borrowed'}">${book.status === 'DISPONIBLE' ? 'Disponible' : 'Indisponible'}</span></td>
                            <td>Physique</td>
                            <td class="action-buttons">
                                <button class="btn btn-danger btn-sm delete-book" data-id="${book.id}" data-type="physical">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                    
                    // Ajouter les écouteurs d'événements pour la suppression
                    document.querySelectorAll('.delete-book').forEach(button => {
                        button.addEventListener('click', function() {
                            const bookId = this.getAttribute('data-id');
                            const type = this.getAttribute('data-type');
                            
                            if (confirm('Êtes-vous sûr de vouloir supprimer ce livre?')) {
                                deleteBook(bookId, type);
                            }
                        });
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des livres:', error));
            
            // Charger les utilisateurs
            fetch('get_data.php?type=users')
                .then(response => response.json())
                .then(users => {
                    const tbody = document.querySelector('#users table tbody');
                    tbody.innerHTML = '';
                    
                    if (users.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">Aucun utilisateur inscrit</td></tr>';
                    } else {
                        users.forEach(user => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${user.first_name} ${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.phone || '-'}</td>
                                <td>${user.role}</td>
                                <td>${new Date(user.created_date).toLocaleDateString()}</td>
                                <td class="action-buttons">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    }
                })
                .catch(error => console.error('Erreur lors du chargement des utilisateurs:', error));
            
            // Charger les réservations en attente
            fetch('get_data.php?type=reservations&status=Demande en cours...')
                .then(response => response.json())
                .then(reservations => {
                    const tbody = document.querySelector('#pending-reservations table tbody');
                    tbody.innerHTML = '';
                    
                    if (reservations.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Aucune réservation en attente</td></tr>';
                    } else {
                        reservations.forEach(reservation => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${reservation.title}</td>
                                <td>${reservation.first_name} ${reservation.name}</td>
                                <td>${new Date(reservation.reservation_date).toLocaleDateString()}</td>
                                <td><span class="status status-pending">${reservation.status}</span></td>
                                <td class="action-buttons">
                                    <button class="btn btn-success btn-sm confirm-reservation" data-id="${reservation.id}">
                                        <i class="fas fa-check"></i> Confirmer
                                    </button>
                                    <button class="btn btn-danger btn-sm reject-reservation" data-id="${reservation.id}">
                                        <i class="fas fa-times"></i> Rejeter
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                        
                        // Ajouter les écouteurs d'événements pour les boutons de réservation
                        document.querySelectorAll('.confirm-reservation').forEach(button => {
                            button.addEventListener('click', function() {
                                const reservationId = this.getAttribute('data-id');
                                processReservation(reservationId, 'confirm');
                            });
                        });
                        
                        document.querySelectorAll('.reject-reservation').forEach(button => {
                            button.addEventListener('click', function() {
                                const reservationId = this.getAttribute('data-id');
                                processReservation(reservationId, 'reject');
                            });
                        });
                    }
                })
                .catch(error => console.error('Erreur lors du chargement des réservations:', error));
        }

        // Fonction pour supprimer un livre
        function deleteBook(bookId, type) {
            const formData = new FormData();
            formData.append('action', 'delete_book');
            formData.append('book_id', bookId);
            formData.append('type', type);
            
            fetch('process_books.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    loadData(); // Recharger les données
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Erreur lors de la suppression: ' + error, 'error');
            });
        }

        // Fonction pour traiter une réservation (confirmer ou rejeter)
        function processReservation(reservationId, action) {
            const formData = new FormData();
            formData.append('action', action);
            formData.append('reservation_id', reservationId);
            
            fetch('process_reservations.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    loadData(); // Recharger les données
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Erreur: ' + error, 'error');
            });
        }

        // Modifier la fonction de soumission du formulaire d'ajout de livre
        document.getElementById('submit-add-book').addEventListener('click', () => {
            const formData = new FormData();
            const isPhysical = document.querySelector('.book-type-option[data-type="physical"]').classList.contains('selected');
            
            formData.append('action', 'add_book');
            formData.append('type', isPhysical ? 'physical' : 'digital');
            formData.append('title', document.getElementById('book-title').value);
            formData.append('author', document.getElementById('book-author').value);
            formData.append('category', document.getElementById('book-category').value);
            formData.append('description', document.getElementById('book-description').value);
            
            if (isPhysical) {
                formData.append('isbn', document.getElementById('book-isbn').value);
                formData.append('publisher', document.getElementById('book-publisher').value);
                formData.append('publication_date', document.getElementById('book-publication-date').value);
                formData.append('status', document.getElementById('book-status').value);
                
                const coverImage = document.getElementById('book-cover').files[0];
                if (coverImage) {
                    formData.append('cover_image', coverImage);
                }
            } else {
                formData.append('price', document.getElementById('digital-price').value);
                formData.append('publication_date', document.getElementById('digital-publication-date').value);
                formData.append('is_free', document.getElementById('digital-free').checked ? '1' : '0');
                
                const digitalFile = document.getElementById('digital-file').files[0];
                if (digitalFile) {
                    formData.append('digital_file', digitalFile);
                }
                
                const coverImage = document.getElementById('book-cover').files[0];
                if (coverImage) {
                    formData.append('cover_image', coverImage);
                }
            }
            
            fetch('process_books.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    addBookModal.style.display = 'none';
                    document.getElementById('add-book-form').reset();
                    loadData(); // Recharger les données
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Erreur lors de l\'ajout du livre: ' + error, 'error');
            });
        });

        // Charger les données au chargement de la page
        document.addEventListener('DOMContentLoaded', () => {
            loadData();
            showToast('Bienvenue dans l\'administration!');
        });
    </script>
    
</body>
</html>