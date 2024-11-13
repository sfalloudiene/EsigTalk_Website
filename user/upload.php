$chemin_fichier = "uploads/" . $nom_fichier;
if (!file_exists("uploads")) {
    mkdir("uploads", 0777, true);
}
if (move_uploaded_file($_FILES['document']['tmp_name'], $chemin_fichier)) {
    $piece_jointe = $chemin_fichier;
}