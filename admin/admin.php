<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Administrateur</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Page Administrateur</h1>

    <!-- Section Gestion des Utilisateurs -->
    <section id="gestion-utilisateurs">
        <h2>Gestion des Utilisateurs</h2>
        <form action="admin.php" method="POST">
            <label for="user-select">Sélectionner un utilisateur :</label>
            <select name="user_id" id="user-select">
                <!-- Exemple d'option pour un utilisateur, les options seront générées dynamiquement en PHP -->
                <option value="1">Utilisateur1 - Role actuel: Apprenti</option>
                <option value="2">Utilisateur2 - Role actuel: Tuteur</option>
            </select>
            <label for="role">Attribuer un rôle :</label>
            <select name="role" id="role">
                <option value="apprenti">Apprenti</option>
                <option value="tuteur_ecole">Tuteur Ecole</option>
                <option value="tuteur_entreprise">Tuteur Entreprise</option>
            </select>
            <button type="submit" name="assign-role">Attribuer le rôle</button>
        </form>
    </section>

    <!-- Section Gestion des Équipes -->
    <section id="gestion-equipes">
        <h2>Création des Équipes</h2>
        <form action="admin.php" method="POST">
            <label for="apprenti">Sélectionner un apprenti :</label>
            <select name="apprenti_id" id="apprenti">
                <option value="1">Apprenti1</option>
                <option value="2">Apprenti2</option>
            </select>

            <label for="tuteur_ecole">Sélectionner un tuteur école :</label>
            <select name="tuteur_ecole_id" id="tuteur_ecole">
                <option value="3">Tuteur Ecole1</option>
                <option value="4">Tuteur Ecole2</option>
            </select>

            <label for="tuteur_entreprise">Sélectionner un tuteur entreprise :</label>
            <select name="tuteur_entreprise_id" id="tuteur_entreprise">
                <option value="5">Tuteur Entreprise1</option>
                <option value="6">Tuteur Entreprise2</option>
            </select>

            <button type="submit" name="create-equipe">Créer l'équipe</button>
        </form>
    </section>
</body>
</html>
