<?php
include('../param.inc.php');
$apprenti_id = (int)$_GET['apprenti_id'];

// Requête pour récupérer les détails de l'équipe
$stmt = $conn->prepare("
    SELECT u.nom AS apprenti_nom, u.prenom AS apprenti_prenom, u.email AS apprenti_email,
           t1.nom AS tuteur_ecole_nom, t1.prenom AS tuteur_ecole_prenom, t1.email AS tuteur_ecole_email,
           t2.nom AS tuteur_entreprise_nom, t2.prenom AS tuteur_entreprise_prenom, t2.email AS tuteur_entreprise_email
    FROM equipe
    JOIN user u ON equipe.apprenti_id = u.id
    JOIN user t1 ON equipe.tuteur_ecole_id = t1.id
    JOIN user t2 ON equipe.tuteur_entreprise_id = t2.id
    WHERE equipe.apprenti_id = ?
");
$stmt->bind_param("i", $apprenti_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $equipe = $result->fetch_assoc();

    echo "<h3>Apprenti</h3>";
    echo "<p><strong>Nom :</strong> " . htmlspecialchars($equipe['apprenti_nom']) . "</p>";
    echo "<p><strong>Prénom :</strong> " . htmlspecialchars($equipe['apprenti_prenom']) . "</p>";
    echo "<p><strong>Email :</strong> " . htmlspecialchars($equipe['apprenti_email']) . "</p>";
    echo "<hr>";

    echo "<h3>Tuteur École</h3>";
    echo "<p><strong>Nom :</strong> " . htmlspecialchars($equipe['tuteur_ecole_nom']) . "</p>";
    echo "<p><strong>Prénom :</strong> " . htmlspecialchars($equipe['tuteur_ecole_prenom']) . "</p>";
    echo "<p><strong>Email :</strong> " . htmlspecialchars($equipe['tuteur_ecole_email']) . "</p>";
    echo "<hr>";

    echo "<h3>Tuteur Entreprise</h3>";
    echo "<p><strong>Nom :</strong> " . htmlspecialchars($equipe['tuteur_entreprise_nom']) . "</p>";
    echo "<p><strong>Prénom :</strong> " . htmlspecialchars($equipe['tuteur_entreprise_prenom']) . "</p>";
    echo "<p><strong>Email :</strong> " . htmlspecialchars($equipe['tuteur_entreprise_email']) . "</p>";
} else {
    echo "Équipe non trouvée.";
}
?>

