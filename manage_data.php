<?php
session_start();
require_once 'includes/config.php';
require_once 'session_check.php';

// Vérifier si l'utilisateur est administrateur
if (!isConnected() || !isAdmin()) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
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
                            //$stmt = $pdo->prepare("UPDATE books SET status = 'INDISPONIBLE' WHERE id = ?");
                            $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
                            $stmt->execute([$input['book_id']]);
                        }
                        echo json_encode(['success' => true, 'message' => 'Livre supprimé avec succès']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID ou type de livre manquant']);
                    }
                    break;
                    
                case 'delete_user':
                    if (isset($input['user_id'])) {
                        //$stmt = $pdo->prepare("UPDATE users SET status = 'deleted' WHERE id = ?");
                        $stmt = $pdo->prepare("DELETE FROM users  WHERE id = ?");
                        $stmt->execute([$input['user_id']]);
                        echo json_encode(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID utilisateur manquant']);
                    }
                    break;
                    
                case 'return_book':
                    if (isset($input['borrowing_id'])) {
                        $pdo->beginTransaction();
                        
                        // Récupérer l'ID du livre
                        $stmt = $pdo->prepare("SELECT book_id FROM borrowings WHERE id = ?");
                        $stmt->execute([$input['borrowing_id']]);
                        $borrowing = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($borrowing) {
                            // Mettre à jour la date de retour
                            $stmt = $pdo->prepare("UPDATE borrowings SET return_date = NOW() WHERE id = ?");
                            $stmt->execute([$input['borrowing_id']]);
                            
                            // Mettre à jour le statut du livre
                            $stmt = $pdo->prepare("UPDATE books SET status = 'DISPONIBLE' WHERE id = ?");
                            $stmt->execute([$borrowing['book_id']]);
                            
                            $pdo->commit();
                            echo json_encode(['success' => true, 'message' => 'Livre retourné avec succès']);
                        } else {
                            $pdo->rollBack();
                            echo json_encode(['success' => false, 'message' => 'Emprunt non trouvé']);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID emprunt manquant']);
                    }
                    break;
                    
                case 'approve_reservation':
                    if (isset($input['reservation_id'])) {
                        $pdo->beginTransaction();
                        
                        $stmt = $pdo->prepare("
                            UPDATE reservations 
                            SET status = 'Approuvée', 
                                approved_at = NOW(),
                                pickup_deadline = DATE_ADD(NOW(), INTERVAL 7 DAY)
                            WHERE id = ?
                        ");
                        $stmt->execute([$input['reservation_id']]);
                        
                        // Récupérer l'ID du livre pour mettre à jour son statut
                        $stmt = $pdo->prepare("SELECT book_id FROM reservations WHERE id = ?");
                        $stmt->execute([$input['reservation_id']]);
                        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($reservation) {
                            $stmt = $pdo->prepare("UPDATE books SET status = 'INDISPONIBLE' WHERE id = ?");
                            $stmt->execute([$reservation['book_id']]);
                        }
                        
                        $pdo->commit();
                        echo json_encode(['success' => true, 'message' => 'Réservation approuvée avec succès']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID réservation manquant']);
                    }
                    break;
                    
                case 'reject_reservation':
                    if (isset($input['reservation_id'])) {
                        $pdo->beginTransaction();
                        
                        // Récupérer l'ID du livre
                        $stmt = $pdo->prepare("SELECT book_id FROM reservations WHERE id = ?");
                        $stmt->execute([$input['reservation_id']]);
                        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($reservation) {
                            // Mettre à jour la réservation
                            $stmt = $pdo->prepare("
                                UPDATE reservations 
                                SET status = 'Rejetée'
                                WHERE id = ?
                            ");
                            $stmt->execute([$input['reservation_id']]);
                            
                            // Vérifier s'il y a d'autres réservations pour ce livre
                            $stmt = $pdo->prepare("
                                SELECT COUNT(*) as count FROM reservations 
                                WHERE book_id = ? AND status IN ('Demande en cours...', 'Approuvée')
                            ");
                            $stmt->execute([$reservation['book_id']]);
                            $other_reservations = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            // Remettre le livre en disponible s'il n'y a plus de réservations
                            if ($other_reservations['count'] == 0) {
                                $stmt = $pdo->prepare("UPDATE books SET status = 'DISPONIBLE' WHERE id = ?");
                                $stmt->execute([$reservation['book_id']]);
                            }
                            
                            $pdo->commit();
                            echo json_encode(['success' => true, 'message' => 'Réservation rejetée avec succès']);
                        } else {
                            $pdo->rollBack();
                            echo json_encode(['success' => false, 'message' => 'Réservation non trouvée']);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID réservation manquant']);
                    }
                    break;
                    
                case 'convert_reservation':
                    if (isset($input['reservation_id'])) {
                        $pdo->beginTransaction();
                        
                        // Récupérer les informations de la réservation
                        $stmt = $pdo->prepare("
                            SELECT r.user_id, r.book_id 
                            FROM reservations r 
                            WHERE r.id = ? AND r.status = 'Approuvée'
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
                                SET status = 'Emprunté'
                                WHERE id = ?
                            ");
                            $stmt->execute([$input['reservation_id']]);
                            
                            $pdo->commit();
                            echo json_encode(['success' => true, 'message' => 'Réservation convertie en emprunt avec succès']);
                        } else {
                            $pdo->rollBack();
                            echo json_encode(['success' => false, 'message' => 'Réservation non trouvée ou non approuvée']);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'ID réservation manquant']);
                    }
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
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
}
?>