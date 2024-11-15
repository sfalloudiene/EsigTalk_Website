<?php
session_start(); // Pour les messages
 
// Récupération des données du formulaire
$nom = htmlentities($_POST['nom']);
$prenom = htmlentities($_POST['prenom']);
$email = htmlentities($_POST['email']);
$password = htmlentities($_POST['password']);
$role = 0;
 
// Options pour bcrypt
$options = ['cost' => 10];
$password_crypt = password_hash($password, PASSWORD_BCRYPT, $options);
 
// Connexion à la base de données
require_once("param.inc.php");
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die('Erreur de connexion (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
 
// Vérification de l'existence de l'utilisateur
$stmt = $mysqli->prepare("SELECT id FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
 
if ($result->num_rows > 0) {
    // L'utilisateur existe déjà
    $_SESSION['message'] = "Vous avez déjà un compte avec cet e-mail.";
    header('Location: inscription.php');
    exit();
}
 
// Inscription de l'utilisateur si l'email est unique
$stmt = $mysqli->prepare("INSERT INTO user(nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $nom, $prenom, $email, $password_crypt, $role);
 
if ($stmt->execute()) {
    $_SESSION['message'] = "Compte créé avec succès";
    header('Location: confirmation.php');
    exit();
} else {
    $_SESSION['message'] = "Impossible d'enregistrer";
    header('Location: inscription.php');
    exit();
}
?>