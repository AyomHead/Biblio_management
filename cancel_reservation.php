<?php
session_start();
include_once("includes/config.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si l'ID de réservation est fourni
if (!isset($_POST['reservation_id']) || empty($_POST['reservation_id'])) {
    $_SESSION['error_message'] = "Aucune réservation spécifiée pour l'annulation.";
    header("Location: catalogue.php");
    exit();
}

// Récupérer et valider l'ID de réservation
$reservation_id = filter_var($_POST['reservation_id'], FILTER_VALIDATE_INT);
if ($reservation_id === false || $reservation_id <= 0) {
    $_SESSION['error_message'] = "ID de réservation invalide.";
    header("Location: catalogue.php");
    exit();
}

try {
    // Commencer une transaction
    $pdo->beginTransaction();
    
    // 1. Récupérer les informations de la réservation
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ?");
    $stmt->execute([$reservation_id, $_SESSION['id']]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$reservation) {
        $_SESSION['error_message'] = "Réservation non trouvée ou vous n'avez pas l'autorisation de l'annuler.";
        header("Location: catalogue.php");
        exit();
    }
    
    $book_id = $reservation['book_id'];
    
    // 2. Supprimer la réservation
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$reservation_id]);
    
    // 3. Vérifier s'il y a d'autres réservations pour ce livre
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM reservations WHERE book_id = ? AND status IN ('Demande en cours...', 'Approuvée')");
    $stmt->execute([$book_id]);
    $other_reservations = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 4. Remettre le livre en disponible s'il n'y a plus de réservations
    if ($other_reservations['count'] == 0) {
        $stmt = $pdo->prepare("UPDATE books SET status = 'DISPONIBLE' WHERE id = ?");
        $stmt->execute([$book_id]);
    }
    
    // Valider la transaction
    $pdo->commit();
    
    $_SESSION['success_message'] = "Votre réservation a été annulée avec succès.";
    
} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    $_SESSION['error_message'] = "Une erreur s'est produite lors de l'annulation: " . $e->getMessage();
}

// Rediriger vers la page des réservations
header("Location: profil.php");
exit();
?>