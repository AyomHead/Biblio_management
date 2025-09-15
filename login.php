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

            <form>
                <div class="form-group">
                    <input type="email" placeholder="Email : example@gmail.com" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Mot de passe" required>
                </div>
                <div class="form-group text-center">
                    <a href="#" class="form-recovery">Mot de passe oublié</a>
                </div>
                <div class="form-group">
                    <button type="submit">Se connecter</button>
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