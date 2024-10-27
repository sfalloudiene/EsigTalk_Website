<?php
  session_start();
  $titre = "Accueil";

  include('header.inc.php');
  include('menu.inc.php');

  include('message.inc.php');
?>

  <h1>Bienvenue dans EsigTalk</h1>
  <h5>La plateforme qui facilite les echanges entre tuteurs et apprentis</h5>

<?php
  include('footer.inc.php');
?>