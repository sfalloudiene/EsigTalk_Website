<?php
  $titre = "Connexion";

  include('header.inc.php');
?>
<head>
<link rel="stylesheet" href="connexion.css">
</head>
<div class="container-centered">
  <h1>Connexion à votre compte</h1>
  <form>
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Adresse mail</label>
      <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
      <input type="password" class="form-control" id="exampleInputPassword1">
    </div>
    <button type="submit" class="btn btn-primary">Se connecter</button>
  </form>
</div>

<?php
  include('footer.inc.php');
?>
