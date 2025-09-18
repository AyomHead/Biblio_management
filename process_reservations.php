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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $reservationId = $_POST['reservation_id'] ?? 0;
    
    if (empty($reservationId)) {
        echo json_encode(['success' => false, 'message' => 'ID réservation manquant']);
        exit();
    }
    
    try {
        if ($action === 'confirm') {
            $pdo->beginTransaction();
            
            // Mettre à jour le statut de la réservation
            $stmt = $pdo->prepare("UPDATE reservations SET status = 'Approuvée', approved_at = NOW(), pickup_deadline = DATE_ADD(NOW(), INTERVAL 7 DAY) WHERE id = ?");
            $stmt->execute([$reservationId]);
            
            // Récupérer les informations de la réservation
            $stmt = $pdo->prepare("SELECT book_id FROM reservations WHERE id = ?");
            $stmt->execute([$reservationId]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($reservation) {
                // Mettre à jour le statut du livre
                $stmt = $pdo->prepare("UPDATE books SET status = 'INDISPONIBLE' WHERE id = ?");
                $stmt->execute([$reservation['book_id']]);
            }
            
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Réservation confirmée avec succès']);
            
        } elseif ($action === 'reject') {
            $pdo->beginTransaction();
            
            // Récupérer l'ID du livre
            $stmt = $pdo->prepare("SELECT book_id FROM reservations WHERE id = ?");
            $stmt->execute([$reservationId]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($reservation) {
                // Mettre à jour le statut de la réservation
                $stmt = $pdo->prepare("UPDATE reservations SET status = 'Rejetée' WHERE id = ?");
                $stmt->execute([$reservationId]);
                
                // Vérifier s'il y a d'autres réservations pour ce livre
                $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM reservations WHERE book_id = ? AND status IN ('Demande en cours...', 'Approuvée')");
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
            echo json_encode(['success' => false, 'message' => 'Action non valide']);
        }
    } catch (PDOException $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>