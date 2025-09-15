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

            <form>
                <div class="form-group">
                    <input type="text" placeholder="Nom : Chédraques" required>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Prénom : Gilbert" required>
                </div>
                <div class="form-group">
                    <input type="email" placeholder="Email : example@gmail.com" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Mot de passe" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Confirmer le mot de passe" required>
                </div>
                
                <!-- Case à cocher pour administrateur -->
                <div class="admin-check-container">
                    <input type="checkbox" id="adminCheck" class="admin-check">
                    <label for="adminCheck" class="admin-check-label">Je suis un administrateur</label>
                </div>
                
                <!-- Champ code admin (caché par défaut) -->
                <div id="adminCodeField" class="form-group admin-code-field">
                    <input type="password" placeholder="Code administrateur" id="adminCode">
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
                document.getElementById('adminCode').value = ''; // Réinitialiser le champ
            }
        });
    </script>
    <!-- Pied de page -->
    <?php include_once "footer.php" ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>