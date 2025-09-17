<?php
// process_reservations.php
require_once 'includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $reservationId = $_POST['reservation_id'] ?? 0;
    
    try {
        if ($action === 'confirm') {
            // Mettre à jour le statut de la réservation
            $stmt = $pdo->prepare("UPDATE reservations SET status = 'Approuvée', approved_at = NOW() WHERE id = ?");
            $stmt->execute([$reservationId]);
            
            // Récupérer les informations de la réservation
            $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
            $stmt->execute([$reservationId]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($reservation) {
                // Mettre à jour le statut du livre
                $stmt = $pdo->prepare("UPDATE books SET status = 'INDISPONIBLE' WHERE id = ?");
                $stmt->execute([$reservation['book_id']]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Réservation confirmée avec succès']);
            
        } elseif ($action === 'reject') {
            // Mettre à jour le statut de la réservation
            $stmt = $pdo->prepare("UPDATE reservations SET status = 'Rejetée' WHERE id = ?");
            $stmt->execute([$reservationId]);
            
            echo json_encode(['success' => true, 'message' => 'Réservation rejetée avec succès']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}
?>