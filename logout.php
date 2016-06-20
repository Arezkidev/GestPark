<?php
  session_destroy();
  $_SESSION['disconnect']=1;
    if ($_SESSION['disconnect']=="1") // Si le visiteur s'est déconnecté.
    {
      header('Location: index.php');
      exit();
    }
?>
