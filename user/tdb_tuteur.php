<?php
session_start();
include('../param.inc.php'); // Connexion à la base de données

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// Récupération des informations de l'utilisateur depuis la session
$prenom = isset($_SESSION['prenom']) ? $_SESSION['prenom'] : "Tuteur";
$nom = isset($_SESSION['nom']) ? $_SESSION['nom'] : "";

// Récupération de l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Requête pour récupérer les apprentis associés au tuteur connecté
$stmt = $conn->prepare("
    SELECT u.id AS apprenti_id, u.nom, u.prenom
    FROM equipe
    INNER JOIN user u ON equipe.apprenti_id = u.id
    WHERE equipe.tuteur_ecole_id = ? OR equipe.tuteur_entreprise_id = ?
");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$apprentis = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Tuteur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="tdb_tuteur.css">
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
                    <a class="nav-link" href="#" onclick="afficherSection('equipes', this)">Équipes</a>
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
            <h1>Bienvenue, <?php echo htmlspecialchars($prenom . " " . $nom); ?></h1>

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

            <!-- Section Équipes -->
            <section id="equipes" style="display: none;">
                <h2>Vos Équipes</h2>
                <ul>
                    <?php foreach ($apprentis as $apprenti): ?>
                        <li>
                            <a href="#" onclick="afficherEquipe(<?php echo $apprenti['apprenti_id']; ?>)">
                                <?php echo htmlspecialchars($apprenti['prenom'] . ' ' . $apprenti['nom']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div id="details-equipe" style="display: none;">
                    <!-- Informations sur l'équipe sélectionnée, chargées dynamiquement via AJAX -->
                </div>
            </section>

            <!-- Section Nouveau Message -->
            <section id="nouveau-message" style="display: none;">
                <h2>Nouveau Message</h2>
                <p>Sélectionnez une équipe pour envoyer un message :</p>
                <ul>
                    <?php foreach ($apprentis as $apprenti): ?>
                        <li>
                            <a href="#" onclick="afficherFormulaireMessage(<?php echo $apprenti['apprenti_id']; ?>)">
                                <?php echo htmlspecialchars($apprenti['prenom'] . ' ' . $apprenti['nom']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div id="formulaire-message" style="display: none;">
                    <!-- Formulaire pour envoyer un message, chargé dynamiquement via AJAX -->
                </div>
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

        // Afficher les détails de l'équipe sélectionnée
        function afficherEquipe(apprentiId) {
            fetch(`fetch_equipe.php?apprenti_id=${apprentiId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("details-equipe").style.display = "block";
                    document.getElementById("details-equipe").innerHTML = data;
                });
        }

        // Afficher le formulaire de message pour l'équipe sélectionnée
        function afficherFormulaireMessage(apprentiId) {
            fetch(`fetch_formulaire_message.php?apprenti_id=${apprentiId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("formulaire-message").style.display = "block";
                    document.getElementById("formulaire-message").innerHTML = data;
                });
        }
    </script>
</body>
</html>


