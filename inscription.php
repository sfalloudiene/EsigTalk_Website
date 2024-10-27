<?php
  $titre = "Inscription";

  include('header.inc.php');
  include('menu.inc.php');
?>

  <h1>Création d'un compte</h1>
  <form  method="POST" action="tt_inscription.php">
    <div class="container">
    <!-- Nom et prénom sur une ou deux lignes (en fonction de la taille) -->
    <!-- Ici on utilise le breakpoint medium -->
    <div class="row">
        <div class="col-md-6">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control " id="nom" name="nom" placeholder="Votre nom..." required>
        </div>
        <div class="col-md-6">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control " id="prenom" name="prenom" placeholder="Votre prénom..." required>
        </div>
    </div>
    <!-- email et mot de passe -->
    <div class="row">
        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control " id="email" name="email" placeholder="Votre email..." required>
        </div>
        <div class="col-md-6">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control " id="password" name="password" placeholder="Votre mot de passe..." required>
        </div>
    </div>
    <div class="row my-3">
        <div class="d-grid d-md-block">
            <button class="btn btn-outline-primary" type="submit">Inscription</button></div>   
        </div>
    </div>
</form>
 
<?php
  include('footer.inc.php');
?>