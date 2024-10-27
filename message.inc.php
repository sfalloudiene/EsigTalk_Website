  
  <?php
  if(isset($_SESSION['message'])) {

    echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>";
      
      echo $_SESSION["message"];
      echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
    echo "</div>";

    unset($_SESSION['message']);
  }
  ?>