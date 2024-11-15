<?php
session_start();
require_once("param.inc.php");

// Vérifier que l'ID de l'équipe est passé en paramètre
if (isset($_POST['equipe_id']) && !empty($_POST['equipe_id'])) {
    $equipe_id = intval($_POST['equipe_id']);

    // Connexion à la base de données
    $mysqli = new mysqli($host, $login, $passwd, $dbname);
    if ($mysqli->connect_error) {
        die('Erreur de connexion (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    // Supprimer l'équipe de la table
    $stmt = $mysqli->prepare("DELETE FROM equipe WHERE id = ?");
    $stmt->bind_param("i", $equipe_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "L'équipe a été supprimée avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de la suppression de l'équipe.";
    }

    $stmt->close();
    $mysqli->close();

    // Rediriger vers la page admin avec un message
    header("Location: admin1.php");
    exit();
} else {
    $_SESSION['message'] = "Veuillez sélectionner une équipe à supprimer.";
    header("Location: admin1.php");
    exit();
}
?>

