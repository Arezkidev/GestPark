<!DOCTYPE html>
<?php
  session_start();
  if (isset($_SESSION['connect']))//On vérifie que le variable existe.
{
        $connect=$_SESSION['connect'];//On récupère la valeur de la variable de session.
}
else
{
        $connect=0;//Si $_SESSION['connect'] n'existe pas, on donne la valeur "0".
}
       
if ($connect == "1" &&  $_SESSION['role'] == 1) // Si le visiteur s'est identifié.
{
// On affiche la page cachée.
?> 
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>PARKING | Admin</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
	</head>
	<body>

		<!-- Header -->
			<header id="header">
				<h1><a href="#">PARKING</a> | Administrateur</h1>
				<nav id="nav">
					<ul>
						<li><a href="logout.php"<i class="fa fa-sign-out fa-2x"></i></a></li>
					</ul>
				</nav>
			</header>

		<!-- Main -->
			<section id="main" class="wrapper">
				<div class="container">
					<?php
					$pdo = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
					require('fonction.php');
					?>
					<header class="major">
						<h2>Administration du Parking</h2>
						<p>Il y a actuellement {<?php placeTotal(); ?>} places dans le parking dont {<?php placeDispo(); ?>} libre. <br/>
							Vous avez {<?php echo totalDemande(); ?>} demande(s) d'inscription en attente de validation.</p>
					</header>
				</div>
			</section>

			<section id="one" class="wrapper style1 special">
				<div class="container">
					<div class="row 150%">
						<div class="4u 12u$(medium)">
							<a class="tool" href="#places"><section class="box">
								<i class="icon big rounded color1 fa-car"></i>
								<h3>Places</h3>
								<p></p>
							</section></a>
						</div>
						<div class="4u 12u$(medium)">
							<a class="tool" href="#user"><section class="box">
								<i class="icon big rounded color9 fa-group"></i>
								<h3>Utilisateurs - <i class="fa fa-exclamation-circle" style="color:red;"></i></h3>
								<p></p>
							</section></a>
						</div>
						
					</div>
				</div>
			</section>

<div id="place">
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

    if(isset( $_GET['statut']))//quand on appui sur le bouton valider
	{
      $statut = $_GET['statut'];
      if(!empty($statut) && $statut == 'occ')
      {
      	echo listOccupees();
  	  }
      elseif(!empty($statut) && $statut == 'lib')
      {
        echo listLibres();
      }
	}
	if(isset( $_GET['action']))//quand on appui sur le bouton valider
	{  
      $action = $_GET['action'];
    }
    if(isset( $_GET['place']))//quand on appui sur le bouton valider
	{  
     $place = $_GET['place'];
      if(!empty($place) && $action == 'lib')
      {
        echo updatePlaceOcc($place);
      }
    }
      
      if(isset($_POST['btn_attribuer']))
      {
        if(!empty($_POST['attribuer']))
        {
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
</div>

		<section id="one" class="wrapper style1 special">
			<div class="container">
			</div>
		</section>

		<div id="user">
			<section id="two" class="wrapper style2 special">
				<div class="container">
					<header class="major">
						<h2>Gestion des Utilisateurs</h2>
						<p>Vous pouvez voir les <a href="#">Demandes</a> d'Inscription en attente, ou <a href="#">Rechercher</a>,
							un Utilisateur.</p>
					</header>
					<form>
						<input type="text" name="rch_user" placeholder="Rechercher un utilisateur..."/><br/>
						<input type="submit" name="btn_user" value="Recherche"/>
					</form>
						<table>

							<tr>
								<th>Nom</th>
								<th>Prénom</th>
								<th>CP</th>
								<th>Immatriculation</th>
								<th>Mail</th>
								<th>Indicateurs</th>
								<th>Action</th>
							</tr>
							<tr>
	                        <?php
	                          $donnees = $pdo->query('SELECT * FROM utilisateur, demandes_places where utilisateur.IDUser = demandes_places.IDUser and IDPlace is null');
	                          if (isset($_GET['idAttribution'])) 
	                          {
							    attribuerPlace($_GET['idAttribution']);
							  }
	                          while ($ligne = $donnees->fetch()) 
	                          { ?>
	                               <tr>
	                                   <td><?php echo $ligne['Nom']; ?></td>
	                                   <td><?php echo $ligne['Prenom']; ?></td>
	                                   <td><?php echo $ligne['CodePostal']; ?></td>
	                                    <td><?php echo $ligne['Immatriculation']; ?></td>
	                                    <td><?php echo $ligne['AdresseMail']; ?></td>
	                                    <td><?php if($ligne['Priorite']==1)
	                                    		  {
	                                    		  	echo "Faible";
	                                    		  }
	                                    		  else if($ligne['Priorite']==2)
	                                    		  {
	                                    		  	echo "Fort";
	                                    		  }
	                                    		  else
	                                    		  {
	                                    		  	echo "Urgent";
	                                    		  } ?>
	                                    		  		</td>
	                                    <td><a href='admin.php?idAttribution=<?php echo $ligne['IDUser'];?>'>attribuer Place</a></td>
	                                    
	                               </tr>
	                        <?php }


									if(isset($_POST["validerUser"]))//quand on appui sur le bouton valider
		 							{
		 								attribuerPlace();
		 								header('Location: admin.php');
									}
	                         ?>
						</table>
						<form action="admin.php" method="post">
						<input type="submit" name="validerUser" value="Valider"/>
					</form>

			</section>
		</div>

		

		<!-- Footer -->
			
	</body>
</html>
<?php  
}
else
{
 
   header('Location: index.php');
      exit();
}
?>