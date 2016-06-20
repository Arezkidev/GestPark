<?php
  session_start();
  $_SESSION['connect']=0;
    if ($_SESSION['connect']=="0") // Si le visiteur s'est identifié.
    {
      header('Location: index.php');
      exit();
    }
?>