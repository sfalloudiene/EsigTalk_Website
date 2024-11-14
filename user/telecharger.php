<?php
session_start();
include('../param.inc.php'); // Connexion à la base de données

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die("Accès refusé. Vous devez être connecté pour télécharger ce fichier.");
}

$user_id = $_SESSION['user_id'];
$file_id = isset($_GET['file_id']) ? intval($_GET['file_id']) : 0;

// Récupérer le chemin du fichier et vérifier l'autorisation d'accès
$stmt = $conn->prepare("
    SELECT piece_jointe
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
$file_path = $message['piece_jointe'];

// Vérifier que le fichier existe
if (!file_exists($file_path)) {
    die("Fichier non trouvé.");
}

// Forcer le téléchargement du fichier
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . basename($file_path));
header("Content-Length: " . filesize($file_path));
flush(); // Vider le tampon de sortie
readfile($file_path);
exit();
?>
