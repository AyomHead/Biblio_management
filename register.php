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

    // Vérification des champs obligatoires
    $required_fields = ['name', 'first_name', 'email', 'password1', 'password2', 'phone', 'birthdate'];
    foreach ($required_fields as $field) {
        if (empty($regis_data[$field] ?? '')) {
            $error_message = "Tous les champs obligatoires doivent être remplis";
            break;
        }
    }

    if(empty($error_message)) {
        if($regis_data['password1'] !== $regis_data['password2']){
            $error_message = "Les mots de passe ne correspondent pas";
        }
        elseif(!filter_var($regis_data['email'], FILTER_VALIDATE_EMAIL)){
            $error_message = "L'adresse email n'est pas valide";
        }
        else{
            // Nettoyage des données
            $user_name = htmlspecialchars(trim($regis_data['name']));
            $user_first = htmlspecialchars(trim($regis_data['first_name']));
            $user_email = filter_var(trim($regis_data['email']), FILTER_SANITIZE_EMAIL);
            $user_password = $regis_data['password1'];
            $user_phone = htmlspecialchars(trim($regis_data['phone']));
            $user_birthdate = $regis_data['birthdate'];
            // Correction: utilisation de 'adress' au lieu de 'address'
            $user_address = isset($regis_data['adress']) ? htmlspecialchars(trim($regis_data['adress'])) : NULL;
            $user_npi = isset($regis_data['npi']) ? htmlspecialchars(trim($regis_data['npi'])) : NULL;
            $admin_key = isset($regis_data['admin_key']) ? trim($regis_data['admin_key']) : '';

            $user_password_hashed = password_hash($user_password, PASSWORD_BCRYPT);

            try {
                $stmt = $pdo->prepare("SELECT email FROM users WHERE email = ?");
                $stmt->execute([$user_email]);
                $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($existing_user){
                    $error_message = "Un utilisateur avec cet email existe déjà";
                }
                else{
                    // Correction: utilisation de pass_word au lieu de password
                    $inscription = $pdo->prepare("INSERT INTO users (name, first_name, email, pass_word, phone, birthdate, address, npi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $result = $inscription->execute([
                        $user_name,
                        $user_first,
                        $user_email,
                        $user_password_hashed,
                        $user_phone,
                        $user_birthdate,
                        $user_address,
                        $user_npi
                    ]);
                    
                    if($result){
                        if($admin_key && $admin_key === $admin_secure_key){
                            $make_admin = $pdo->prepare("UPDATE users SET role = 'admin' WHERE email = ?");
                            $make_admin->execute([$user_email]);
                        }
                        
                        // Stocker le message dans la session et rediriger
                        $_SESSION['login_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                        header("Location: login.php");
                        exit();
                    } else {
                        $error_message = "Une erreur s'est produite lors de l'inscription. Veuillez réessayer.";
                    }
                }
            } catch (PDOException $e) {
                $error_message = "Erreur de base de données : " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Bibliothèque Nationale du Bénin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/Style.css">
    <style>
        /* Style personnalisé pour le champ date */
        .date-input-container {
            position: relative;
        }
        
        .date-input-container i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #bbb;
            pointer-events: none;
        }
        
        input[type="date"] {
            appearance: none;
            -webkit-appearance: none;
        }
        
        input[type="date"]::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            color: transparent;
            background: transparent;
            cursor: pointer;
        }
        
        input[type="date"]::placeholder {
            color: #bbb;
        }
        
        /* Pour Firefox */
        input[type="date"]::-moz-placeholder {
            color: #bbb;
        }
        
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
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
            <?php if(isset($_SESSION['login_message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['login_message']; unset($_SESSION['login_message']); ?></div>
            <?php endif; ?>
            <?php if($error_message): ?>
                <div class="alert alert-danger"><?= $error_message ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Nom : Chédraques" required value="<?= isset($regis_data['name']) ? htmlspecialchars($regis_data['name']) : '' ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="Prénom : Gilbert" required value="<?= isset($regis_data['first_name']) ? htmlspecialchars($regis_data['first_name']) : '' ?>">
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email : example@gmail.com" required value="<?= isset($regis_data['email']) ? htmlspecialchars($regis_data['email']) : '' ?>">
                </div>
                
                <!-- Champs obligatoires -->
                <div class="form-group">
                    <input type="tel" name="phone" placeholder="Téléphone : +229 12 34 56 78" required value="<?= isset($regis_data['phone']) ? htmlspecialchars($regis_data['phone']) : '' ?>">
                </div>
                <div class="form-group date-input-container">
                    <input type="date" name="birthdate" id="birthdate" placeholder="Date de naissance" required value="<?= isset($regis_data['birthdate']) ? htmlspecialchars($regis_data['birthdate']) : '' ?>">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                
                <!-- Champs optionnels -->
                <div class="form-group">
                    <input type="text" name="adress" id="adress" placeholder="Adresse (optionnel)" value="<?= isset($regis_data['adress']) ? htmlspecialchars($regis_data['adress']) : '' ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="npi" placeholder="Numéro de pièce d'identité (optionnel)" value="<?= isset($regis_data['npi']) ? htmlspecialchars($regis_data['npi']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <input type="password" name="password1" placeholder="Mot de passe" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password2" placeholder="Confirmer le mot de passe" required>
                </div>
                
                <!-- Case à cocher pour administrateur -->
                <div class="admin-check-container">
                    <input type="checkbox" id="adminCheck" class="admin-check" <?= isset($regis_data['admin_key']) ? 'checked' : '' ?>>
                    <label for="adminCheck" class="admin-check-label">Je suis un administrateur</label>
                </div>
                
                <!-- Champ code admin (caché par défaut) -->
                <div id="adminCodeField" class="form-group admin-code-field" style="display: <?= isset($regis_data['admin_key']) ? 'block' : 'none' ?>;">
                    <input type="password" name="admin_key" placeholder="Code administrateur" id="adminCode" value="<?= isset($regis_data['admin_key']) ? htmlspecialchars($regis_data['admin_key']) : '' ?>">
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

        // Gestion du placeholder pour le champ date
        const birthdateInput = document.getElementById('birthdate');
        
        // Fonction pour gérer l'affichage du placeholder
        function handleDatePlaceholder() {
            if (!birthdateInput.value) {
                birthdateInput.setAttribute('data-placeholder', 'Date de naissance');
            } else {
                birthdateInput.removeAttribute('data-placeholder');
            }
        }
        
        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            handleDatePlaceholder();
            
            // Écouteurs d'événements
            birthdateInput.addEventListener('change', handleDatePlaceholder);
            birthdateInput.addEventListener('blur', handleDatePlaceholder);
            birthdateInput.addEventListener('input', handleDatePlaceholder);
        });
    </script>

    <!-- Pied de page -->
    <?php include_once "footer.php" ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>