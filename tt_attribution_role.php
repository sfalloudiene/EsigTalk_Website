<?php
session_start();
require_once("param.inc.php");

// Vérifie que l'ID de l'utilisateur et le rôle ont bien été envoyés
if (isset($_POST['user_id'], $_POST['role'])) {
    $user_id = intval($_POST['user_id']);
    $role = intval($_POST['role']);

    // Connexion à la base de données
    $mysqli = new mysqli($host, $login, $passwd, $dbname);
    if ($mysqli->connect_error) {
        die('Erreur de connexion (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    // Préparation de la requête de mise à jour du rôle
    if ($stmt = $mysqli->prepare("UPDATE user SET role = ? WHERE id = ?")) {
        $stmt->bind_param("ii", $role, $user_id);

        // Exécute la requête et vérifie si elle a réussi
        if ($stmt->execute()) {
            $_SESSION['message'] = "Le rôle a été attribué avec succès.";
        } else {
            $_SESSION['message'] = "Erreur : impossible d'attribuer le rôle.";
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Erreur de préparation de la requête.";
    }

    $mysqli->close();
} else {
    $_SESSION['message'] = "Veuillez sélectionner un utilisateur et un rôle.";
}

// Redirection vers la page d'administration
header('Location: admin1.php');
exit;
?>
