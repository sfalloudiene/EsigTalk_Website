<?php
session_start();
include('../param.inc.php'); // Connexion à la base de données

// Récupération de l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Vérifier que l'utilisateur est connecté
if (!isset($user_id)) {
    header("Location: connexion.php");
    exit();
}

// Requête pour récupérer les informations de l’équipe de l’apprenti connecté
$stmt = $conn->prepare("
    SELECT 
        u1.nom AS nom_tuteur_entreprise, u1.prenom AS prenom_tuteur_entreprise, u1.email AS email_tuteur_entreprise,
        u2.nom AS nom_tuteur_ecole, u2.prenom AS prenom_tuteur_ecole, u2.email AS email_tuteur_ecole
    FROM equipe
    INNER JOIN user u1 ON equipe.tuteur_entreprise_id = u1.id
    INNER JOIN user u2 ON equipe.tuteur_ecole_id = u2.id
    WHERE equipe.apprenti_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$equipe = $result->fetch_assoc();

// Récupérer les informations de l'utilisateur connecté
$stmt = $conn->prepare("SELECT nom, prenom FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Apprenti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="tdb_apprenti.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-light p-3">
            <h4 class="text-primary">Menu</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#" onclick="afficherSection('messages-recus', this)">Messages Reçus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('messages-envoyes', this)">Messages Envoyés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('messages-non-lus', this)">Messages Non Lus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('equipe', this)">Équipe</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('nouveau-message', this)">Nouveau Message</a>
                </li>
            </ul>
            <hr>
            <a href="../connexion.php" class="btn btn-danger mt-3">Déconnexion</a>
        </nav>

        <!-- Contenu Principal -->
        <div class="content p-4">
            <h1>Bienvenue, <?php echo htmlspecialchars($user['prenom'] . " " . $user['nom']); ?></h1>

            <!-- Section des Messages Reçus -->
            <section id="messages-recus">
                <h2>Vos Messages Reçus</h2>
                <?php
                $stmt = $conn->prepare("
                    SELECT messages.*, user.email AS expediteur_email
                    FROM messages
                    INNER JOIN user ON messages.expediteur_id = user.id
                    WHERE messages.destinataire_id = ?
                    ORDER BY messages.date_envoi DESC
                ");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($message = $result->fetch_assoc()) {
                        $message_class = $message['lu'] ? "" : "non-lu";
                        echo "<a href='voir_message.php?id=" . $message['id'] . "' class='message-link'>";
                        echo "<div class='message $message_class'>";
                        echo "<h3>" . htmlspecialchars($message['sujet']) . "</h3>";
                        echo "<p><strong>Expéditeur:</strong> " . htmlspecialchars($message['expediteur_email']) . "</p>";
                        echo "<p><strong>Date:</strong> " . $message['date_envoi'] . "</p>";
                        echo "<p><strong>Catégorie:</strong> " . htmlspecialchars($message['categorie']) . "</p>";
                        echo "<p>" . htmlspecialchars($message['contenu']) . "</p>";
                        if ($message['piece_jointe_blob']) {
                            echo "<p><strong>Pièce jointe :</strong> <a href='telecharger.php?file_id=" . $message['id'] . "'>Télécharger</a></p>";
                        }
                        echo "</div></a>";
                    }
                } else {
                    echo "<p>Vous n'avez aucun message dans votre boîte de réception.</p>";
                }
                ?>
            </section>

            <!-- Section des Messages Envoyés -->
            <section id="messages-envoyes" style="display: none;">
                <h2>Vos Messages Envoyés</h2>
                <?php
                $stmt = $conn->prepare("
                    SELECT messages.*, user.email AS destinataire_email
                    FROM messages
                    INNER JOIN user ON messages.destinataire_id = user.id
                    WHERE messages.expediteur_id = ?
                    ORDER BY messages.date_envoi DESC
                ");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($message = $result->fetch_assoc()) {
                        echo "<a href='voir_message.php?id=" . $message['id'] . "' class='message-link'>";
                        echo "<div class='message'>";
                        echo "<h3>" . htmlspecialchars($message['sujet']) . "</h3>";
                        echo "<p><strong>Destinataire:</strong> " . htmlspecialchars($message['destinataire_email']) . "</p>";
                        echo "<p><strong>Date:</strong> " . $message['date_envoi'] . "</p>";
                        echo "<p><strong>Catégorie:</strong> " . htmlspecialchars($message['categorie']) . "</p>";
                        echo "<p>" . htmlspecialchars($message['contenu']) . "</p>";
                        if ($message['piece_jointe_blob']) {
                            echo "<p><strong>Pièce jointe :</strong> <a href='telecharger.php?file_id=" . $message['id'] . "'>Télécharger</a></p>";
                        }
                        echo "</div></a>";
                    }
                } else {
                    echo "<p>Vous n'avez aucun message envoyé.</p>";
                }
                ?>
            </section>

            <!-- Section des Messages Non Lus -->
            <section id="messages-non-lus" style="display: none;">
                <h2>Vos Messages Non Lus</h2>
                <?php
                $stmt = $conn->prepare("
                    SELECT messages.*, user.email AS expediteur_email
                    FROM messages
                    INNER JOIN user ON messages.expediteur_id = user.id
                    WHERE messages.destinataire_id = ? AND messages.lu = 0
                    ORDER BY messages.date_envoi DESC
                ");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($message = $result->fetch_assoc()) {
                        echo "<a href='voir_message.php?id=" . $message['id'] . "' class='message-link'>";
                        echo "<div class='message non-lu'>";
                        echo "<h3>" . htmlspecialchars($message['sujet']) . "</h3>";
                        echo "<p><strong>Expéditeur:</strong> " . htmlspecialchars($message['expediteur_email']) . "</p>";
                        echo "<p><strong>Date:</strong> " . $message['date_envoi'] . "</p>";
                        echo "<p><strong>Catégorie:</strong> " . htmlspecialchars($message['categorie']) . "</p>";
                        echo "<p>" . htmlspecialchars($message['contenu']) . "</p>";
                        if ($message['piece_jointe_blob']) {
                            echo "<p><strong>Pièce jointe :</strong> <a href='telecharger.php?file_id=" . $message['id'] . "'>Télécharger</a></p>";
                        }
                        echo "</div></a>";
                    }
                } else {
                    echo "<p>Vous n'avez aucun message non lu.</p>";
                }
                ?>
            </section>

            <!-- Section Équipe -->
            <section id="equipe" style="display: none;">
                <h2>Votre Équipe</h2>
                <div class="team-member">
                    <h3>Tuteur Entreprise</h3>
                    <p><strong>Nom:</strong> <?php echo htmlspecialchars($equipe['nom_tuteur_entreprise']); ?></p>
                    <p><strong>Prénom:</strong> <?php echo htmlspecialchars($equipe['prenom_tuteur_entreprise']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($equipe['email_tuteur_entreprise']); ?></p>
                </div>
                <hr>
                <div class="team-member">
                    <h3>Tuteur École</h3>
                    <p><strong>Nom:</strong> <?php echo htmlspecialchars($equipe['nom_tuteur_ecole']); ?></p>
                    <p><strong>Prénom:</strong> <?php echo htmlspecialchars($equipe['prenom_tuteur_ecole']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($equipe['email_tuteur_ecole']); ?></p>
                </div>
            </section>

            <!-- Section Nouveau Message -->
            <section id="nouveau-message" style="display: none;">
                <h2>Nouveau Message</h2>
                <form method="POST" action="envoyer_message.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="destinataires" class="form-label">Destinataires</label>
                        <select id="destinataires" name="destinataires[]" class="form-control" multiple required>
                            <option value="<?php echo htmlspecialchars($equipe['email_tuteur_entreprise']); ?>">
                                Tuteur Entreprise: <?php echo htmlspecialchars($equipe['nom_tuteur_entreprise'] . " " . $equipe['prenom_tuteur_entreprise']); ?>
                            </option>
                            <option value="<?php echo htmlspecialchars($equipe['email_tuteur_ecole']); ?>">
                                Tuteur École: <?php echo htmlspecialchars($equipe['nom_tuteur_ecole'] . " " . $equipe['prenom_tuteur_ecole']); ?>
                            </option>
                        </select>
                        <small>Vous pouvez sélectionner un ou plusieurs destinataires.</small>
                    </div>
                    <div class="mb-3">
                        <label for="sujet" class="form-label">Sujet</label>
                        <input type="text" class="form-control" id="sujet" name="sujet" required>
                    </div>
                    <div class="mb-3">
                        <label for="categorie" class="form-label">Catégorie</label>
                        <select id="categorie" name="categorie" class="form-control" required>
                            <option value="Suivi">Suivi</option>
                            <option value="Info">Info</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="contenu" class="form-label">Message</label>
                        <textarea class="form-control" id="contenu" name="contenu" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="document" class="form-label">Pièce jointe (PDF uniquement)</label>
                        <input type="file" class="form-control" id="document" name="document" accept=".pdf">
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </section>
        </div>
    </div>

    <script>
        function afficherSection(sectionId, element) {
            document.querySelectorAll('.content section').forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            element.classList.add('active');
        }
    </script>
</body>
</html>
