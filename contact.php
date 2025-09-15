<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Bibliothèque Nationale du Bénin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="css/Style.css">
</head>
<body>
    <!-- Barre de navigation -->
    <?php include_once "header.php"; ?>

    <!-- Contenu principal -->
    <div class="main-content">
        <div class="contact-container">
            <h2 class="contact-title">
                <i class="fas fa-envelope me-2"></i>Contactez-nous
            </h2>

            <div class="contact-area">
                <div class="form-card">
                    <h3 style="margin-bottom:18px;color:#fff;font-size:20px; text-align: center;">Envoyez-nous un message</h3>

                    <div class="form-row">
                        <div class="form-col">
                            <label for="name">Nom complet</label>
                            <input id="name" type="text" placeholder="Votre nom" />
                        </div>
                        <div class="form-col">
                            <label for="email">Adresse e-mail</label>
                            <input id="email" type="email" placeholder="exemple@mail.com" />
                        </div>
                    </div>

                    <div class="form-col" style="margin-bottom: 20px;">
                        <label for="subject">Objet</label>
                        <input id="subject" type="text" placeholder="Sujet de votre message" />
                    </div>

                    <div class="form-col" style="margin-bottom: 25px;">
                        <label for="message">Message</label>
                        <textarea id="message" placeholder="Votre message..."></textarea>
                    </div>

                    <button class="btn-contact">Envoyer le message</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <?php include_once "footer.php"; ?>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>