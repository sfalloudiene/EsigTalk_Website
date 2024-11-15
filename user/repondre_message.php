<?php
session_start();
include('../param.inc.php'); // Connexion à la base de données

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer les informations du message d'origine
$stmt = $conn->prepare("
    SELECT expediteur_id, destinataire_id, sujet
    FROM messages
    WHERE id = ?
");
$stmt->bind_param("i", $message_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $message = $result->fetch_assoc();
    
    // Déterminer les rôles de l'expéditeur et du destinataire pour la réponse
    $expediteur_id = $user_id;
    $destinataire_id = ($message['expediteur_id'] == $user_id) ? $message['destinataire_id'] : $message['expediteur_id'];
    $sujet_reponse = "RE: " . $message['sujet']; // Sujet constant pour le fil de discussion
} else {
    echo "Message d'origine non trouvé.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Répondre au Message</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Répondre : <?php echo htmlspecialchars($sujet_reponse); ?></h2>
        <form method="POST" action="envoyer_reponse.php" enctype="multipart/form-data">
            <!-- Champ caché pour associer la réponse au message d'origine -->
            <input type="hidden" name="reponse_a_id" value="<?php echo $message_id; ?>">
            <input type="hidden" name="expediteur_id" value="<?php echo $expediteur_id; ?>">
            <input type="hidden" name="destinataire_id" value="<?php echo $destinataire_id; ?>">

            <div class="mb-3">
                <label for="sujet" class="form-label">Sujet</label>
                <input type="text" class="form-control" id="sujet" name="sujet" value="<?php echo htmlspecialchars($sujet_reponse); ?>" readonly>
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
    </div>
</body>
</html>

