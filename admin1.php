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
    <title>Gestion des utilisateurs - Attribution de rôles</title>
    <link rel="stylesheet" href="admin1.css">
</head>
<body>

<div class="container-centered">
    <h1>Gestion des rôles des utilisateurs</h1>

    <!-- Formulaire de sélection d'un utilisateur et d'attribution d'un rôle -->
    <form method="POST" action="tt_attribution_role.php">
        <div class="container">
            <label for="user_select" class="form-label">Sélectionner un utilisateur :</label>
            <select id="user_select" name="user_id" class="form-control" required>
                <option value="">-- Choisir un utilisateur --</option>
                <?php
                // Boucle pour afficher chaque utilisateur dans le menu déroulant
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

</div>

</body>
</html>

<?php
$mysqli->close(); // Ferme la connexion à la base de données
?>
