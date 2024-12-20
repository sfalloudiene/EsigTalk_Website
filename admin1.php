<?php 
session_start();
require_once("param.inc.php");

// Connexion à la base de données
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die('Erreur de connexion (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Récupération de tous les utilisateurs de la table "user"
$result = $mysqli->query("SELECT id, nom, prenom, email, role FROM user");
if (!$result) {
    die("Erreur lors de la récupération des utilisateurs : " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs - Administration</title>
    <link rel="stylesheet" href="admin1.css">
</head>
<body>

<div class="container-centered">
    <h1>Administration : Gestion des Rôles et Équipes</h1>

    <!-- Bouton de déconnexion -->
    <form action="deconnexion.php" method="POST" style="text-align: right; margin-bottom: 20px;">
        <button type="submit" class="btn btn-outline-danger">Déconnexion</button>
    </form>

    <!-- Section : Attribuer un rôle -->
    <hr>
    <h2>Attribuer un Rôle</h2>
    <form method="POST" action="tt_attribution_role.php">
        <div class="container">
            <label for="user_select" class="form-label">Sélectionner un utilisateur :</label>
            <select id="user_select" name="user_id" class="form-control" required>
                <option value="">-- Choisir un utilisateur --</option>
                <?php
                while ($user = $result->fetch_assoc()) {
                    $roleLabel = ($user['role'] == 0) ? 'Inactif' : ($user['role'] == 1 ? 'Apprenti' : ($user['role'] == 2 ? 'Tuteur Entreprise' : ($user['role'] == 3 ? 'Tuteur École' : 'Admin')));
                    echo "<option value='{$user['id']}'>{$user['nom']} {$user['prenom']} ({$user['email']}) - Rôle actuel : {$roleLabel}</option>";
                }
                ?>
            </select>

            <label for="role_select" class="form-label mt-3">Attribuer un rôle :</label>
            <select id="role_select" name="role" class="form-control" required>
                <option value="">-- Choisir un rôle --</option>
                <option value="1">Apprenti</option>
                <option value="2">Tuteur Entreprise</option>
                <option value="3">Tuteur École</option>
            </select>

            <div class="d-grid d-md-block my-3">
                <button type="submit" class="btn btn-outline-primary">Attribuer le rôle</button>
            </div>
        </div>
    </form>

    <!-- Message de confirmation ou d'erreur -->
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p class='message'>{$_SESSION['message']}</p>";
        unset($_SESSION['message']); // Supprime le message après affichage
    }
    ?>

    <!-- Section : Créer une équipe -->
    <hr>
    <h2>Créer une Équipe</h2>
    <form method="POST" action="tt_creation_equipe.php">
        <div class="container">
            <label for="apprenti_select" class="form-label">Sélectionner un apprenti :</label>
            <select id="apprenti_select" name="apprenti_id" class="form-control" required>
                <option value="">-- Choisir un apprenti --</option>
                <?php
                $apprenti_result = $mysqli->query("SELECT id, nom, prenom FROM user WHERE role = 1");
                while ($apprenti = $apprenti_result->fetch_assoc()) {
                    echo "<option value='{$apprenti['id']}'>{$apprenti['nom']} {$apprenti['prenom']}</option>";
                }
                ?>
            </select>

            <label for="tuteur_ecole_select" class="form-label mt-3">Sélectionner un tuteur école :</label>
            <select id="tuteur_ecole_select" name="tuteur_ecole_id" class="form-control">
                <option value="">-- Choisir un tuteur école --</option>
                <?php
                $tuteur_ecole_result = $mysqli->query("SELECT id, nom, prenom FROM user WHERE role = 3");
                while ($tuteur_ecole = $tuteur_ecole_result->fetch_assoc()) {
                    echo "<option value='{$tuteur_ecole['id']}'>{$tuteur_ecole['nom']} {$tuteur_ecole['prenom']}</option>";
                }
                ?>
            </select>

            <label for="tuteur_entreprise_select" class="form-label mt-3">Sélectionner un tuteur entreprise :</label>
            <select id="tuteur_entreprise_select" name="tuteur_entreprise_id" class="form-control">
                <option value="">-- Choisir un tuteur entreprise --</option>
                <?php
                $tuteur_entreprise_result = $mysqli->query("SELECT id, nom, prenom FROM user WHERE role = 2");
                while ($tuteur_entreprise = $tuteur_entreprise_result->fetch_assoc()) {
                    echo "<option value='{$tuteur_entreprise['id']}'>{$tuteur_entreprise['nom']} {$tuteur_entreprise['prenom']}</option>";
                }
                ?>
            </select>

            <div class="d-grid d-md-block my-3">
                <button type="submit" class="btn btn-outline-primary">Créer l'équipe</button>
            </div>
        </div>
    </form>

    <!-- Section : Supprimer une équipe -->
    <hr>
    <h2>Supprimer une Équipe</h2>
    <form method="POST" action="supprimer_equipe.php">
        <div class="container">
            <label for="equipe_select" class="form-label">Sélectionner une équipe :</label>
            <select id="equipe_select" name="equipe_id" class="form-control" required>
                <option value="">-- Choisir une équipe --</option>
                <?php
                $equipes = $mysqli->query("
                    SELECT equipe.id AS equipe_id, u.nom AS apprenti_nom, u.prenom AS apprenti_prenom,
                           t1.nom AS tuteur_ecole_nom, t1.prenom AS tuteur_ecole_prenom,
                           t2.nom AS tuteur_entreprise_nom, t2.prenom AS tuteur_entreprise_prenom
                    FROM equipe
                    INNER JOIN user u ON equipe.apprenti_id = u.id
                    LEFT JOIN user t1 ON equipe.tuteur_ecole_id = t1.id
                    LEFT JOIN user t2 ON equipe.tuteur_entreprise_id = t2.id
                ");
                if ($equipes && $equipes->num_rows > 0) {
                    while ($equipe = $equipes->fetch_assoc()) {
                        echo "<option value='{$equipe['equipe_id']}'>";
                        echo htmlspecialchars($equipe['apprenti_prenom'] . " " . $equipe['apprenti_nom']);
                        echo " - Tuteur École: " . htmlspecialchars($equipe['tuteur_ecole_prenom'] . " " . $equipe['tuteur_ecole_nom']);
                        echo " - Tuteur Entreprise: " . htmlspecialchars($equipe['tuteur_entreprise_prenom'] . " " . $equipe['tuteur_entreprise_nom']);
                        echo "</option>";
                    }
                } else {
                    echo "<option value=''>Aucune équipe existante</option>";
                }
                ?>
            </select>

            <div class="d-grid d-md-block my-3">
                <button type="submit" class="btn btn-outline-danger">Supprimer</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>

<?php
$mysqli->close(); // Ferme la connexion à la base de données
?>