<?php
session_start();
include_once("includes/config.php");
include_once("auth_functions.php");

// Initilisation des messages d’erreurs
$login_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $get_coordonnees = $_POST; // récupération de l’email et du mot de passe

    if (!areAvalaible($get_coordonnees['email'] ?? '', $get_coordonnees['password'] ?? '')) {
        $error_message = "Vous devez remplir tous les champs";
    } elseif (!filter_var($get_coordonnees['email'], FILTER_VALIDATE_EMAIL)) {
        $error_message = "L’adresse email n’est pas valide";
    } else {
        $email = $get_coordonnees['email'];
        $password = $get_coordonnees['password'];

        // Dans login.php, la requête ne s'exécute pas !
$stmt = $pdo->prepare("SELECT id, name, first_name, email, pass_word, role FROM users WHERE email = ?");
$stmt->execute([$email]); // ← CETTE LIGNE MANQUE !
$user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['pass_word'])) {
            logedInUser($user);
            $login_message = 'Content de vous revoir ' . $user['first_name'] . ' !';
            // Redirection vers la page d'accueil ou autre après connexion
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Identifiants incorrects";
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
                <h2>Connexion</h2>
                <p>Veuillez entrer vos identifiants</p>
            </div>

            <!-- Affichage des messages -->
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php if ($login_message): ?>
                <div class="alert alert-success"><?php echo $login_message; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email : example@gmail.com" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Mot de passe" required>
                </div>
                <div class="form-group text-center">
                    <a href="#" class="form-recovery">Mot de passe oublié</a>
                </div>
                <div class="form-group">
                    <button type="submit">Se connecter</button>
                </div>
                <div class="text-center">
                    <p>Pas encore inscrit? <a href="register.php">Créez un compte</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Pied de page -->
    <?php include_once "footer.php" ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>