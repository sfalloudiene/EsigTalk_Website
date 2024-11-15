<?php
  // Paramètre de connexion à la BDD (à créer)
  $host="localhost";
  $login="grp_11_7";
  $passwd="fRcRmJ4l3x4jUH";
  $dbname="bdd_11_7";
 
  // Créer la connexion
$conn = new mysqli($host, $login, $passwd, $dbname);
 
// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>