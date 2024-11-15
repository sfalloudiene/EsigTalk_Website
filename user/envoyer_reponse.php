<?php
session_start();
include('../param.inc.php'); // Connexion à la base de données

$expediteur_id = $_SESSION['user_id'];
$destinataire_id = $_POST['destinataire_id'];
$sujet = $_POST['sujet'];
$contenu = $_POST['contenu'];
$reponse_a_id = $_POST['reponse_a_id'];
$piece_jointe_blob = "";

// Gestion de la pièce jointe
if (isset($_FILES['document']) && $_FILES['document']['error'] === 0) {
    $nom_fichier = basename($_FILES['document']['name']);
    $chemin_fichier = "uploads/" . $nom_fichier;
    if (!file_exists("uploads")) {
        mkdir("uploads", 0777, true);
    }
    if (move_uploaded_file($_FILES['document']['tmp_name'], $chemin_fichier)) {
        $piece_jointe_blob = $chemin_fichier;
    }
}

// Insérer la réponse dans la base de données
$stmt = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, sujet, contenu, piece_jointe_blob, reponse_a_id) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisssi", $expediteur_id, $destinataire_id, $sujet, $contenu, $piece_jointe_blob, $reponse_a_id);
$stmt->execute();

header("Location: voir_message.php?id=" . $reponse_a_id);
exit();
?>

