<?php
session_start();
require_once 'includes/config.php';
require_once 'session_check.php';

// Vérifier si l'utilisateur est connecté et est administrateur
if (!isConnected() || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Accès non autorisé']);
    exit();
}

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($input['action'])) {
            switch ($input['action']) {
                case 'delete_book':
                    if (isset($input['book_id']) && isset($input['book_type'])) {
                        if ($input['book_type'] === 'physical') {
                            $stmt = $pdo->prepare("UPDATE books SET status = 'DELETED' WHERE id = ?");
                            $stmt->execute([$input['book_id']]);
                        } else {
                            $stmt = $pdo->prepare("UPDATE digital_books SET status = 'DELETED' WHERE id = ?");
                            $stmt->execute([$input['book_id']]);
                        }
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID ou type de livre manquant']);
                    }
                    break;
                    
                case 'delete_user':
                    if (isset($input['user_id'])) {
                        $stmt = $pdo->prepare("UPDATE users SET status = 'DELETED' WHERE id = ?");
                        $stmt->execute([$input['user_id']]);
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID utilisateur manquant']);
                    }
                    break;
                    
                case 'return_book':
                    if (isset($input['borrowing_id'])) {
                        $stmt = $pdo->prepare("
                            UPDATE borrowings 
                            SET return_date = NOW() 
                            WHERE id = ?
                        ");
                        $stmt->execute([$input['borrowing_id']]);
                        
                        // Mettre à jour le statut du livre
                        $stmt = $pdo->prepare("
                            UPDATE books 
                            SET status = 'DISPONIBLE' 
                            WHERE id = (
                                SELECT book_id FROM borrowings WHERE id = ?
                            )
                        ");
                        $stmt->execute([$input['borrowing_id']]);
                        
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID emprunt manquant']);
                    }
                    break;
                    
                case 'approve_reservation':
                    if (isset($input['reservation_id'])) {
                        $stmt = $pdo->prepare("
                            UPDATE reservations 
                            SET status = 'APPROVED', 
                                approval_date = NOW(),
                                expiration_date = DATE_ADD(NOW(), INTERVAL 7 DAY)
                            WHERE id = ?
                        ");
                        $stmt->execute([$input['reservation_id']]);
                        
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID réservation manquant']);
                    }
                    break;
                    
                case 'reject_reservation':
                    if (isset($input['reservation_id']) && isset($input['reason'])) {
                        $stmt = $pdo->prepare("
                            UPDATE reservations 
                            SET status = 'CANCELLED', 
                                cancellation_date = NOW(),
                                cancellation_reason = ?
                            WHERE id = ?
                        ");
                        $stmt->execute([$input['reason'], $input['reservation_id']]);
                        
                        // Remettre le livre en disponible
                        $stmt = $pdo->prepare("
                            UPDATE books 
                            SET status = 'DISPONIBLE' 
                            WHERE id = (
                                SELECT book_id FROM reservations WHERE id = ?
                            )
                        ");
                        $stmt->execute([$input['reservation_id']]);
                        
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID réservation ou raison manquant']);
                    }
                    break;
                    
                case 'convert_reservation':
                    if (isset($input['reservation_id'])) {
                        // Récupérer les informations de la réservation
                        $stmt = $pdo->prepare("
                            SELECT r.user_id, r.book_id 
                            FROM reservations r 
                            WHERE r.id = ?
                        ");
                        $stmt->execute([$input['reservation_id']]);
                        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($reservation) {
                            // Créer un nouvel emprunt
                            $stmt = $pdo->prepare("
                                INSERT INTO borrowings (user_id, book_id, borrow_date, due_date)
                                VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY))
                            ");
                            $stmt->execute([$reservation['user_id'], $reservation['book_id']]);
                            
                            // Marquer la réservation comme complétée
                            $stmt = $pdo->prepare("
                                UPDATE reservations 
                                SET status = 'COMPLETED',
                                    completion_date = NOW()
                                WHERE id = ?
                            ");
                            $stmt->execute([$input['reservation_id']]);
                            
                            echo json_encode(['success' => true]);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Réservation non trouvée']);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID réservation manquant']);
                    }
                    break;
                    
                case 'delete_reservation':
                    if (isset($input['reservation_id'])) {
                        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
                        $stmt->execute([$input['reservation_id']]);
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID réservation manquant']);
                    }
                    break;
                    
                case 'add_book':
                    // Gérer l'ajout de livre via formulaire
                    if ($_POST['book_type'] === 'physical') {
                        $stmt = $pdo->prepare("
                            INSERT INTO books (title, author, category, isbn, publisher, publication_date, description, status)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([
                            $_POST['title'],
                            $_POST['author'],
                            $_POST['category'],
                            $_POST['isbn'] ?? null,
                            $_POST['publisher'] ?? null,
                            $_POST['publication_date'] ?? null,
                            $_POST['description'],
                            $_POST['status'] ?? 'DISPONIBLE'
                        ]);
                    } else {
                        $stmt = $pdo->prepare("
                            INSERT INTO digital_books (title, author, category, publication_date, description, price, is_free)
                            VALUES (?, ?, ?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([
                            $_POST['title'],
                            $_POST['author'],
                            $_POST['category'],
                            $_POST['publication_date'] ?? null,
                            $_POST['description'],
                            $_POST['price'] ?? 0,
                            isset($_POST['is_free']) ? 1 : 0
                        ]);
                    }
                    echo json_encode(['success' => true]);
                    break;
                    
                default:
                    echo json_encode(['success' => false, 'message' => 'Action non supportée']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Action non spécifiée']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
}
?>