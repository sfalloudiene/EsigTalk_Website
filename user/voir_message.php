<?php
session_start();
include('../param.inc.php'); // Connexion à la base de données

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer l'ID du message depuis l'URL
$message_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Mettre à jour le message pour le marquer comme lu
$stmt_update = $conn->prepare("UPDATE messages SET lu = 1 WHERE id = ? AND destinataire_id = ?");
$stmt_update->bind_param("ii", $message_id, $user_id);
$stmt_update->execute();
$stmt_update->close();

// Récupérer les détails du message
$stmt = $conn->prepare("
    SELECT messages.*, user.email AS expediteur_email, user_dest.email AS destinataire_email
    FROM messages
    INNER JOIN user ON messages.expediteur_id = user.id
    INNER JOIN user AS user_dest ON messages.destinataire_id = user_dest.id
    WHERE messages.id = ?
");

if (!$stmt) {
    die("Erreur SQL : " . $conn->error);
}

$stmt->bind_param("i", $message_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $message = $result->fetch_assoc();
} else {
    echo "Message non trouvé.";
    exit();
}

// Récupérer les réponses associées au message
$stmt_reponses = $conn->prepare("
    SELECT messages.*, user.email AS expediteur_email, user_dest.email AS destinataire_email
    FROM messages
    INNER JOIN user ON messages.expediteur_id = user.id
    INNER JOIN user AS user_dest ON messages.destinataire_id = user_dest.id
    WHERE messages.reponse_a_id = ?
    ORDER BY messages.date_envoi ASC
");

if (!$stmt_reponses) {
    die("Erreur SQL pour les réponses : " . $conn->error);
}

$stmt_reponses->bind_param("i", $message_id);
$stmt_reponses->execute();
$reponses = $stmt_reponses->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Voir Message</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2><?php echo htmlspecialchars($message['sujet']); ?></h2>
        <p><strong>Expéditeur :</strong> <?php echo htmlspecialchars($message['expediteur_email']); ?></p>
        <p><strong>Destinataire :</strong> <?php echo htmlspecialchars($message['destinataire_email']); ?></p>
        <p><strong>Date :</strong> <?php echo $message['date_envoi']; ?></p>
        <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($message['categorie']); ?></p>
        <hr>
        <p><?php echo nl2br(htmlspecialchars($message['contenu'])); ?></p>
        <?php if ($message['piece_jointe_blob']): ?>
            <p><strong>Pièce jointe :</strong> <a href="telecharger.php?file_id=<?php echo $message['id']; ?>">Télécharger</a></p>
        <?php endif; ?>
        <a href="repondre_message.php?id=<?php echo $message['id']; ?>" class="btn btn-primary mt-3">Répondre</a>
        <a href="tdb_tuteur.php" class="btn btn-secondary mt-3">Retour au tableau de bord</a>

        <!-- Affichage des réponses -->
        <?php if ($reponses->num_rows > 0): ?>
            <h3 class="mt-4">Réponses</h3>
            <?php while ($reponse = $reponses->fetch_assoc()): ?>
                <div class="mt-3 p-3 border">
                    <p><strong>Expéditeur :</strong> <?php echo htmlspecialchars($reponse['expediteur_email']); ?></p>
                    <p><strong>Date :</strong> <?php echo $reponse['date_envoi']; ?></p>
                    <hr>
                    <p><?php echo nl2br(htmlspecialchars($reponse['contenu'])); ?></p>
                    <?php if ($reponse['piece_jointe_blob']): ?>
                        <p><strong>Pièce jointe :</strong> <a href="telecharger.php?file_id=<?php echo $reponse['id']; ?>">Télécharger</a></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Aucune réponse pour ce message.</p>
        <?php endif; ?>
    </div>
</body>
</html>



