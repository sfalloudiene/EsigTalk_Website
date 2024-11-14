<?php
session_start();
include('../param.inc.php'); // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expediteur_id = $_SESSION['user_id'];
    $sujet = $_POST['sujet'];
    $contenu = $_POST['contenu'];
    $categorie = $_POST['categorie'];
    $piece_jointe = "";

    // Gestion de la pièce jointe
    if (isset($_FILES['document']) && $_FILES['document']['error'] === 0) {
        $nom_fichier = basename($_FILES['document']['name']);
        $chemin_fichier = "uploads/" . uniqid() . "_" . $nom_fichier;

        // Vérifier si le dossier "uploads" existe, sinon le créer
        if (!file_exists("uploads")) {
            mkdir("uploads", 0755, true); // Crée le dossier avec les permissions nécessaires
        }

        // Déplacer le fichier téléchargé dans le dossier "uploads"
        if (move_uploaded_file($_FILES['document']['tmp_name'], $chemin_fichier)) {
            $piece_jointe = $chemin_fichier; // Enregistrer le chemin du fichier joint dans la base de données
        }
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
            $stmt = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, sujet, contenu, categorie, piece_jointe) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissss", $expediteur_id, $destinataire_id, $sujet, $contenu, $categorie, $piece_jointe);
            $stmt->execute();
        }
    }

    // Redirection après envoi
    header("Location: tdb_apprenti.php");
    exit();
}
?>



