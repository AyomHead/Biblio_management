<?php session_start(); ?>

<?php
include_once("includes/config.php");
include_once("auth_functions.php");

// Initialisation des messages
$login_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $regis_data = $_POST;

    $admin_secure_key = "MOt4cl5eSeu7Rep03oura40DmIn";

    if(!areAvalaible($regis_data['name'] ?? '', $regis_data['first_name'] ?? '', $regis_data['email'] ?? '', $regis_data['password1'] ?? '', $regis_data['password2'] ?? '')){
        $error_message = "Vous devez remplir tous les champs";
    }
    elseif($regis_data['password1'] !== $regis_data['password2']){
        $error_message = "Les mots de passe ne correspondent pas";
    }
    elseif(!filter_var($regis_data['email'], FILTER_VALIDATE_EMAIL)){
        $error_message = "L'adresse email n'est pas valide";
    }
    else{
        $user_name = $regis_data['name'] ?? '';
        $user_first = $regis_data['first_name'] ?? '';
        $user_email = $regis_data['email'] ?? '';
        $user_password = $regis_data['password1'] ?? '';
        $admin_key = $regis_data['admin_key'] ?? '';

        $user_password = password_hash($user_password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->execute([$user_email]);
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($existing_user){
            $error_message = "Un utilisateur avec cet email existe déjà";
        }
        else{
            $inscription = $pdo->prepare("INSERT INTO users (name, first_name, email, pass_word) VALUES (?, ?, ?, ?)");
            $inscription->execute([
                $user_name,
                $user_first,
                $user_email,
                $user_password
            ]);
            
            if($admin_key && $admin_key === $admin_secure_key){
                $make_admin = $pdo->prepare("UPDATE users SET role = 'admin' WHERE email = ?");
                $make_admin->execute([$user_email]);
            }
            
            $login_message = "Inscription réussie !";
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Bibliothèque Nationale du Bénin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/Style.css">
</head>
<body>
    <!-- Barre de navigation -->
    <?php include_once "header.php"; ?>

    <!-- Contenu principal -->
    <div class="main-content">
        <div class="form-container">
            <div class="form-header">
                <h1>Bibliothèque Nationale du Bénin</h1>
                <h2>Inscription</h2>
                <p>Créez votre compte</p>
            </div>

            <!-- Affichage des messages -->
            <?php if($login_message): ?>
                <div class="alert alert-success"><?= $login_message ?></div>
            <?php endif; ?>
            <?php if($error_message): ?>
                <div class="alert alert-danger"><?= $error_message ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Nom : Chédraques" required>
                </div>
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="Prénom : Gilbert" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email : example@gmail.com" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password1" placeholder="Mot de passe" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password2" placeholder="Confirmer le mot de passe" required>
                </div>
                
                <!-- Case à cocher pour administrateur -->
                <div class="admin-check-container">
                    <input type="checkbox" id="adminCheck" class="admin-check">
                    <label for="adminCheck" class="admin-check-label">Je suis un administrateur</label>
                </div>
                
                <!-- Champ code admin (caché par défaut) -->
                <div id="adminCodeField" class="form-group admin-code-field" style="display: none;">
                    <input type="password" name="admin_key" placeholder="Code administrateur" id="adminCode">
                </div>
                
                <div class="form-group">
                    <button type="submit">S'inscrire</button>
                </div>
                <div class="text-center">
                    <p>Déjà inscrit? <a class="connect" href="login.php">Connectez-vous</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Script pour afficher/masquer le champ code admin
        document.getElementById('adminCheck').addEventListener('change', function() {
            const adminCodeField = document.getElementById('adminCodeField');
            if (this.checked) {
                adminCodeField.style.display = 'block';
            } else {
                adminCodeField.style.display = 'none';
                document.getElementById('adminCode').value = '';
            }
        });
    </script>

    <!-- Pied de page -->
    <?php include_once "footer.php" ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>