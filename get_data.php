<?php
// get_data.php
require_once 'includes/config.php';

header('Content-Type: application/json');

$dataType = $_GET['type'] ?? '';

try {
    if ($dataType === 'books') {
        $stmt = $pdo->query("SELECT * FROM books ORDER BY title");
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($books);
        
    } elseif ($dataType === 'digital_books') {
        $stmt = $pdo->query("SELECT * FROM digital_documents ORDER BY title");
        $digitalBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($digitalBooks);
        
    } elseif ($dataType === 'users') {
        $stmt = $pdo->query("SELECT id, name, first_name, email, phone, role, created_date FROM users ORDER BY created_date DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        
    } elseif ($dataType === 'borrowings') {
        $status = $_GET['status'] ?? '';
        
        if ($status === 'current') {
            $stmt = $pdo->query("
                SELECT b.*, u.name, u.first_name, bk.title 
                FROM borrowings b 
                JOIN users u ON b.user_id = u.id 
                JOIN books bk ON b.book_id = bk.id 
                WHERE b.return_date IS NULL 
                ORDER BY b.borrow_date DESC
            ");
        } elseif ($status === 'overdue') {
            $stmt = $pdo->query("
                SELECT b.*, u.name, u.first_name, bk.title 
                FROM borrowings b 
                JOIN users u ON b.user_id = u.id 
                JOIN books bk ON b.book_id = bk.id 
                WHERE b.return_date IS NULL AND b.due_date < NOW() 
                ORDER BY b.due_date ASC
            ");
        } else {
            $stmt = $pdo->query("
                SELECT b.*, u.name, u.first_name, bk.title 
                FROM borrowings b 
                JOIN users u ON b.user_id = u.id 
                JOIN books bk ON b.book_id = bk.id 
                ORDER BY b.borrow_date DESC
            ");
        }
        
        $borrowings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($borrowings);
        
    } elseif ($dataType === 'reservations') {
        $status = $_GET['status'] ?? '';
        
        if ($status) {
            $stmt = $pdo->prepare("
                SELECT r.*, u.name, u.first_name, b.title 
                FROM reservations r 
                JOIN users u ON r.user_id = u.id 
                JOIN books b ON r.book_id = b.id 
                WHERE r.status = ? 
                ORDER BY r.reservation_date DESC
            ");
            $stmt->execute([$status]);
        } else {
            $stmt = $pdo->query("
                SELECT r.*, u.name, u.first_name, b.title 
                FROM reservations r 
                JOIN users u ON r.user_id = u.id 
                JOIN books b ON r.book_id = b.id 
                ORDER BY r.reservation_date DESC
            ");
        }
        
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($reservations);
        
    } elseif ($dataType === 'stats') {
        // Statistiques pour le tableau de bord
        $stats = [];
        
        // Nombre total de livres
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM books");
        $stats['total_books'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Nombre total d'utilisateurs
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Nombre d'emprunts en cours
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM borrowings WHERE return_date IS NULL");
        $stats['current_borrowings'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Nombre de retards
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM borrowings WHERE return_date IS NULL AND due_date < NOW()");
        $stats['overdue_borrowings'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        echo json_encode($stats);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur lors de la récupération des données: ' . $e->getMessage()]);
}
?>