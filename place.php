<?php
require('fonction.php');
 ?>
<section id="two" class="wrapper style2 special">
  <div class="container">
    <header class="major">
      <h2>Gestion des Places</h2>
      <p>Vous pouvez voir les <a href="admin.php?statut=occ">Places Occupées</a>, consulter les <a href="admin.php?statut=lib">Places Libres</a>,
        ou encore <a href="#">Editer</a> le Parking.</p>
    </header>

    <form action="admin.php" method="POST">
      <input type="text" name="rch_park" placeholder="Rechercher une place..."/><br/>
      <input type="submit" name="btn_park" value="Recherche"/>
    </form>
    <?php
      if(isset($_POST['btn_park'])){
				$rch = $_POST['rch_park'];
        echo searchP($rch);
			}
     ?><br/>
     <?php
      $statut = $_GET['statut'];
      if(!empty($statut) && $statut == 'occ'){
        echo listOccupees();
      }
      elseif(!empty($statut) && $statut == 'lib'){
        echo listLibres();
      }
      $action = $_GET['action'];
      $place = $_GET['place'];
      if(!empty($place) && $action == 'lib'){
        echo updatePlaceOcc($place);
      }
      if(isset($_POST['btn_attribuer'])){
        if(!empty($_POST['attribuer'])){
          $immat = $_POST['attribuer'];
          echo updatePlaceLib($immat);
        }
      }
      ?>
  </div>
</section>
<section id="one" class="wrapper style1 special">
	<div class="container">
	</div>
</section>
