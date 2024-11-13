<?php
session_start();
include('../param.inc.php'); // Connexion à la base de données

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// Récupérer l'ID du message depuis l'URL
$message_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

// Marquer le message comme lu
$stmt = $conn->prepare("UPDATE messages SET lu = 1 WHERE id = ? AND destinataire_id = ?");
$stmt->bind_param("ii", $message_id, $user_id);
$stmt->execute();

// Récupérer les détails du message
$stmt = $conn->prepare("
    SELECT messages.*, user.email AS expediteur_email
    FROM messages
    INNER JOIN user ON messages.expediteur_id = user.id
    WHERE messages.id = ? AND messages.destinataire_id = ?
");
$stmt->bind_param("ii", $message_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $message = $result->fetch_assoc();
} else {
    echo "Message non trouvé.";
    exit();
}
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
        <p><strong>Date :</strong> <?php echo $message['date_envoi']; ?></p>
        <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($message['categorie']); ?></p>
        <hr>
        <p><?php echo nl2br(htmlspecialchars($message['contenu'])); ?></p>
        <?php if ($message['piece_jointe']): ?>
            <p><strong>Pièce jointe :</strong> <a href="<?php echo htmlspecialchars($message['piece_jointe']); ?>" target="_blank">Télécharger</a></p>
        <?php endif; ?>
        <a href="tdb_apprenti.php" class="btn btn-primary mt-3">Retour au tableau de bord</a>
    </div>
</body>
</html>
