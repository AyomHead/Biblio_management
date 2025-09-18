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
    
    if ($action === 'add_book') {
        $type = $_POST['type'] ?? 'physical';
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $category = $_POST['category'] ?? '';
        $description = $_POST['description'] ?? '';
        
        // Validation des champs obligatoires
        if (empty($title) || empty($author) || empty($category) || empty($description)) {
            echo json_encode(['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis']);
            exit();
        }
        
        try {
            if ($type === 'physical') {
                $isbn = $_POST['isbn'] ?? null;
                $publisher = $_POST['publisher'] ?? null;
                $publication_date = $_POST['publication_date'] ?? null;
                $status = $_POST['status'] ?? 'DISPONIBLE';
                
                // Gestion de l'upload de l'image de couverture
                $cover_image = null;
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/covers/';
                    if (!file_exists($uploadDir)) {
                        if (!mkdir($uploadDir, 0777, true)) {
                            echo json_encode(['success' => false, 'message' => 'Impossible de créer le dossier d\'upload']);
                            exit();
                        }
                    }
                    
                    $fileName = uniqid() . '_' . basename($_FILES['cover_image']['name']);
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath)) {
                        $cover_image = $targetPath;
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'upload de l\'image']);
                        exit();
                    }
                }
                
                $stmt = $pdo->prepare("
                    INSERT INTO books (title, author, category, isbn, publisher, publication_date, status, description, cover_image)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $stmt->execute([$title, $author, $category, $isbn, $publisher, $publication_date, $status, $description, $cover_image]);
                
            } else if ($type === 'digital') {
                $price = $_POST['price'] ?? 0.00;
                $publication_date = $_POST['publication_date'] ?? null;
                $is_free = isset($_POST['is_free']) ? 1 : 0;
                
                // Gestion de l'upload du fichier PDF
                $file_path = null;
                $cover_image = null;
                
                if (isset($_FILES['digital_file']) && $_FILES['digital_file']['error'] === UPLOAD_ERR_OK) {
                    // Vérifier la taille du fichier (max 20MB)
                    if ($_FILES['digital_file']['size'] > 20 * 1024 * 1024) {
                        echo json_encode(['success' => false, 'message' => 'Le fichier ne doit pas dépasser 20MB']);
                        exit();
                    }
                    
                    // Vérifier que c'est un PDF
                    $fileType = strtolower(pathinfo($_FILES['digital_file']['name'], PATHINFO_EXTENSION));
                    if ($fileType !== 'pdf') {
                        echo json_encode(['success' => false, 'message' => 'Seuls les fichiers PDF sont autorisés']);
                        exit();
                    }
                    
                    $uploadDir = 'uploads/documents/';
                    if (!file_exists($uploadDir)) {
                        if (!mkdir($uploadDir, 0777, true)) {
                            echo json_encode(['success' => false, 'message' => 'Impossible de créer le dossier d\'upload']);
                            exit();
                        }
                    }
                    
                    $fileName = uniqid() . '_' . basename($_FILES['digital_file']['name']);
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['digital_file']['tmp_name'], $targetPath)) {
                        $file_path = $targetPath;
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'upload du fichier']);
                        exit();
                    }
                }
                
                // Gestion de l'upload de l'image de couverture pour le document numérique
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/covers/';
                    if (!file_exists($uploadDir)) {
                        if (!mkdir($uploadDir, 0777, true)) {
                            echo json_encode(['success' => false, 'message' => 'Impossible de créer le dossier d\'upload']);
                            exit();
                        }
                    }
                    
                    $fileName = uniqid() . '_' . basename($_FILES['cover_image']['name']);
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath)) {
                        $cover_image = $targetPath;
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'upload de l\'image']);
                        exit();
                    }
                }
                
                $stmt = $pdo->prepare("
                    INSERT INTO digital_documents (title, author, description, file_path, cover_image, price, category, publication_date, is_free)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $stmt->execute([$title, $author, $description, $file_path, $cover_image, $price, $category, $publication_date, $is_free]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Livre ajouté avec succès']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout du livre: ' . $e->getMessage()]);
        }
    }
    
    if ($action === 'delete_book') {
        $bookId = $_POST['book_id'] ?? 0;
        $type = $_POST['type'] ?? 'physical';
        
        if (empty($bookId)) {
            echo json_encode(['success' => false, 'message' => 'ID livre manquant']);
            exit();
        }
        
        try {
            if ($type === 'physical') {
                $stmt = $pdo->prepare("UPDATE books SET status = 'DELETED' WHERE id = ?");
            } else {
                $stmt = $pdo->prepare("UPDATE digital_documents SET status = 'DELETED' WHERE id = ?");
            }
            
            $stmt->execute([$bookId]);
            echo json_encode(['success' => true, 'message' => 'Livre supprimé avec succès']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression: ' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>