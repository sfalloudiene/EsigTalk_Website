<?php
session_start();
include('../param.inc.php'); // Connexion à la base de données
 
if (!isset($_SESSION['user_id'])) {
    die("Accès refusé. Vous devez être connecté pour télécharger ce fichier.");
}
 
$user_id = $_SESSION['user_id'];
$file_id = isset($_GET['file_id']) ? intval($_GET['file_id']) : 0;
 
// Récupérer le fichier de la base de données
$stmt = $conn->prepare("
    SELECT piece_jointe_blob, sujet
    FROM messages
    WHERE id = ? AND (expediteur_id = ? OR destinataire_id = ?)
");
$stmt->bind_param("iii", $file_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
 
if ($result->num_rows === 0) {
    die("Accès refusé ou fichier non trouvé.");
}
 
$message = $result->fetch_assoc();
$file_content = $message['piece_jointe_blob'];
$file_name = $message['sujet'] . ".pdf";
 
// Forcer le téléchargement du fichier
header("Content-Description: File Transfer");
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=" . $file_name);
echo $file_content;
exit();
?>
Dispose d’un menu contextuel


Dispose d’un menu contextuel