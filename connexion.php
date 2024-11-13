<?php
  $titre = "Connexion";
  include('header.inc.php');
  session_start();
  
  // Vérifier si un message d'erreur a été défini dans la session (après tentative de connexion)
  $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "";
  unset($_SESSION['error_message']); // Supprime l'erreur de la session après affichage
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="connexion.css">
</head>
<body>
<div class="container-centered">
    <h1>Connexion à votre compte</h1>
    <?php
    // Affichage du message d'erreur s'il y en a un
    if (!empty($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse mail</label>
            <input type="email" name="email" class="form-control" id="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>
</body>
</html>

<?php
  include('footer.inc.php');
?>
