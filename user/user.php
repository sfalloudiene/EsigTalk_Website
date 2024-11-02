<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page - Apprenti ou Tuteur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-light p-3">
            <h4 class="text-primary">Menu</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#messages-recus">Messages Reçus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#messages-envoyes">Messages Envoyés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#messages-non-lus">Messages Non Lus</a>
                </li>
            </ul>
            <hr>
            <a href="deconnexion.php" class="btn btn-danger mt-3">Déconnexion</a>
        </nav>

        <!-- Contenu Principal -->
        <div class="content p-4">
            <h1>Bienvenue, [Nom de l'utilisateur]</h1>

            <!-- Section des Messages Reçus -->
            <section id="messages-recus">
                <h2>Vos Messages Reçus</h2>
                <div class="message non-lu">
                    <h3>Titre du message (Non lu)</h3>
                    <p><strong>Expéditeur:</strong> Tuteur Ecole</p>
                    <p><strong>Date:</strong> 01/11/2024</p>
                    <p><strong>Catégorie:</strong> Suivi</p>
                    <p>Contenu du message: Voici un message qui n'a pas encore été lu.</p>
                    <p><strong>Pièce jointe:</strong> <a href="document.pdf" target="_blank">document.pdf</a></p>
                    <div class="message-actions">
                        <a href="repondre.php?message_id=1" class="btn btn-primary btn-sm">Répondre</a>
                        <a href="supprimer.php?message_id=1" class="btn btn-danger btn-sm">Supprimer</a>
                    </div>
                </div>
                <!-- Ajoutez plus de messages ici -->
            </section>

            <!-- Section des Messages Envoyés -->
            <section id="messages-envoyes" class="mt-4">
                <h2>Vos Messages Envoyés</h2>
                <div class="message">
                    <h3>Titre du message envoyé</h3>
                    <p><strong>Destinataire:</strong> Tuteur Entreprise</p>
                    <p><strong>Date:</strong> 27/10/2024</p>
                    <p><strong>Catégorie:</strong> Info</p>
                    <p>Contenu du message: Voici le contenu du message que vous avez envoyé.</p>
                    <p><strong>Pièce jointe:</strong> <a href="document_envoye.pdf" target="_blank">document_envoye.pdf</a></p>
                </div>
                <!-- Ajoutez plus de messages envoyés ici -->
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
