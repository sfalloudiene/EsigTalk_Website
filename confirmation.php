<?php
session_start();
$titre = "Confirmation d'inscription";
include('header.inc.php');
?>
<head>
<link rel="stylesheet" href="connexion.css">
</head>
 
<div class="container-centered">
    <h1>Confirmation d'inscription</h1>
 
    <!-- Affichage du message de succès -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success mt-3">
            <?php echo $_SESSION['message']; ?>
            <?php unset($_SESSION['message']); // Supprimer le message après l'affichage ?>
        </div>
    <?php endif; ?>
 
    <!-- Bouton de connexion -->
    <div class="text-center mt-4">
        <a href="connexion.php" class="btn btn-primary">Se connecter</a>
    </div>
</div>
 
<?php
include('footer.inc.php');
?>