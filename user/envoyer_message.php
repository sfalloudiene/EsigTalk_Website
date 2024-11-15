<?php
session_start();
include('../param.inc.php'); // Connexion à la base de données
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expediteur_id = $_SESSION['user_id'];
    $sujet = $_POST['sujet'];
    $contenu = $_POST['contenu'];
    $categorie = $_POST['categorie'];
    $piece_jointe_blob = null;
 
    // Gestion de la pièce jointe
    if (isset($_FILES['document']) && $_FILES['document']['error'] === 0) {
        $file_content = file_get_contents($_FILES['document']['tmp_name']);
        $piece_jointe_blob = $file_content; // Lire le contenu du fichier pour l'insérer dans la base de données
    }
 
    // Parcourir les destinataires sélectionnés et insérer le message
    foreach ($_POST['destinataires'] as $destinataire_email) {
        $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->bind_param("s", $destinataire_email);
        $stmt->execute();
        $result = $stmt->get_result();
 
        if ($result->num_rows > 0) {
            $destinataire = $result->fetch_assoc();
            $destinataire_id = $destinataire['id'];
 
            // Insérer le message dans la base de données pour chaque destinataire
            $stmt = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, sujet, contenu, categorie, piece_jointe_blob) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisssb", $expediteur_id, $destinataire_id, $sujet, $contenu, $categorie, $piece_jointe_blob);
            $stmt->send_long_data(5, $piece_jointe_blob); // Envoie de données longues pour le blob
            $stmt->execute();
        }
    }
 
    // Redirection après envoi
    header("Location: tdb_apprenti.php");
    exit();
}
?>