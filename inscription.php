<?php
session_start(); // Important pour afficher les messages de session
$titre = "Inscription";
include('header.inc.php');
?>
<head>
<link rel="stylesheet" href="connexion.css">
</head>
<div class="container-centered">
    <h1>Création d'un compte</h1>
 
    <!-- Affichage du message d'erreur ou de succès -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-warning">
            <?php echo $_SESSION['message']; ?>
        </div>
    <?php endif; ?>
 
    <!-- Formulaire d'inscription -->
    <form method="POST" action="tt_inscription.php">
        <div class="container">
            <!-- Nom et prénom -->
            <div class="row">
                <div class="col-md-6">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom..." required>
                </div>
                <div class="col-md-6">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Votre prénom..." required>
                </div>
            </div>
            <!-- Email et mot de passe -->
            <div class="row">
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Votre email..." required>
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe..." required>
                </div>
            </div>
            <div class="row my-3">
                <div class="d-grid d-md-block">
                    <button class="btn btn-outline-primary" type="submit">Inscription</button>
                </div>  
            </div>
        </div>
    </form>
</div>
 
<?php
include('footer.inc.php');
?>