<?php
session_start();
require_once("param.inc.php");

// Connexion à la base de données
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die('Erreur de connexion (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Récupère les données du formulaire
$apprenti_id = $_POST['apprenti_id'];
$tuteur_ecole_id = $_POST['tuteur_ecole_id'];
$tuteur_entreprise_id = $_POST['tuteur_entreprise_id'];

// Récupérer le nom et prénom de l'apprenti pour nommer l'équipe
$apprenti_result = $mysqli->query("SELECT nom, prenom FROM user WHERE id = $apprenti_id");
$apprenti = $apprenti_result->fetch_assoc();
$team_name = $apprenti['nom'] . ' ' . $apprenti['prenom'];

// Insérer l'équipe dans la table `equipe`
$stmt = $mysqli->prepare("INSERT INTO equipe (nom, apprenti_id, tuteur_ecole_id, tuteur_entreprise_id) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    // Affiche le message d'erreur en cas d'échec de la préparation
    die("Erreur de préparation de la requête : " . $mysqli->error);
}

$stmt->bind_param("siii", $team_name, $apprenti_id, $tuteur_ecole_id, $tuteur_entreprise_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "L'équipe '$team_name' a été créée avec succès.";
} else {
    $_SESSION['message'] = "Erreur lors de la création de l'équipe.";
}

$stmt->close();
$mysqli->close();

// Redirection vers la page admin avec un message de confirmation
header('Location: admin1.php');
exit;
?>
