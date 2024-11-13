<?php
  // Paramètre de connexion à la BDD (à créer)
  $host="localhost";
  $login="root";
  $passwd="root";
  $dbname="test";

  // Créer la connexion
$conn = new mysqli($host, $login, $passwd, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>