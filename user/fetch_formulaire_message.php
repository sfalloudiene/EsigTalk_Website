<?php
session_start();
include('../param.inc.php');

// Récupération de l'ID du tuteur connecté
$expediteur_id = $_SESSION['user_id'];

// Vérifier que l'utilisateur est connecté
if (!isset($expediteur_id)) {
    echo "Utilisateur non authentifié.";
    exit();
}

// Récupérer l'ID de l'apprenti sélectionné depuis l'URL
$apprenti_id = (int)$_GET['apprenti_id'];

// Requête pour récupérer les e-mails de l'apprenti et du second tuteur
$stmt = $conn->prepare("
    SELECT u.email AS apprenti_email, 
           CASE 
               WHEN equipe.tuteur_ecole_id = ? THEN t2.email 
               WHEN equipe.tuteur_entreprise_id = ? THEN t1.email 
           END AS second_tuteur_email,
           tuteur.email AS expediteur_email
    FROM equipe
    JOIN user u ON equipe.apprenti_id = u.id
    JOIN user t1 ON equipe.tuteur_ecole_id = t1.id
    JOIN user t2 ON equipe.tuteur_entreprise_id = t2.id
    JOIN user tuteur ON tuteur.id = ?
    WHERE equipe.apprenti_id = ?
");
$stmt->bind_param("iiii", $expediteur_id, $expediteur_id, $expediteur_id, $apprenti_id);
$stmt->execute();
$result = $stmt->get_result();
$equipe = $result->fetch_assoc();

// Vérifier que l'équipe existe
if (!$equipe) {
    echo "Équipe non trouvée.";
    exit();
}
?>

<form method="POST" action="envoyer_message.php" enctype="multipart/form-data">
    <!-- Expéditeur automatiquement rempli en fonction de l'utilisateur connecté -->
    <div class="mb-3">
        <label for="expediteur" class="form-label">Expéditeur</label>
        <input type="email" class="form-control" id="expediteur" name="expediteur" value="<?php echo htmlspecialchars($equipe['expediteur_email']); ?>" readonly>
    </div>

    <!-- Destinataires : apprenti et second tuteur uniquement -->
    <div class="mb-3">
        <label for="destinataires" class="form-label">Destinataires</label>
        <select id="destinataires" name="destinataires[]" class="form-control" multiple required>
            <option value="<?php echo htmlspecialchars($equipe['apprenti_email']); ?>">
                Apprenti : <?php echo htmlspecialchars($equipe['apprenti_email']); ?>
            </option>
            <option value="<?php echo htmlspecialchars($equipe['second_tuteur_email']); ?>">
                Second Tuteur : <?php echo htmlspecialchars($equipe['second_tuteur_email']); ?>
            </option>
        </select>
        <small>Vous pouvez sélectionner un ou plusieurs destinataires (maintenez Ctrl/Cmd pour en choisir plusieurs).</small>
    </div>

    <!-- Sujet du message -->
    <div class="mb-3">
        <label for="sujet" class="form-label">Sujet</label>
        <input type="text" class="form-control" id="sujet" name="sujet" required>
    </div>

    <!-- Catégorie du message -->
    <div class="mb-3">
        <label for="categorie" class="form-label">Catégorie</label>
        <select id="categorie" name="categorie" class="form-control" required>
            <option value="Suivi">Suivi</option>
            <option value="Info">Info</option>
        </select>
    </div>

    <!-- Contenu du message -->
    <div class="mb-3">
        <label for="contenu" class="form-label">Message</label>
        <textarea class="form-control" id="contenu" name="contenu" rows="5" required></textarea>
    </div>

    <!-- Pièce jointe -->
    <div class="mb-3">
        <label for="document" class="form-label">Pièce jointe (PDF uniquement)</label>
        <input type="file" class="form-control" id="document" name="document" accept=".pdf">
    </div>

    <!-- Bouton de soumission -->
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>


