<!DOCTYPE html>
<?php
	session_start();

?>
<html lang="en">
	<head>

		<meta charset="UTF-8">
		<title>PARKING | Utilisateur</title>
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
				<h1><a href="index.php">PARKING</a> | Utilisateur</h1>
				<!-- <nav id="nav">
					<ul>
						<li><a href="index.php">Acceuil</a></li>
						<li><a href="generic.html">Generic</a></li>
						<li><a href="elements.html">Elements</a></li>
						<li><a href="#" class="button special">Sign Up</a></li>
					</ul>
				</nav> -->
				<nav id="nav">
					<ul>
						<li><a href="logout.php"<i class="fa fa-sign-out fa-2x"></i></a></li>
					</ul>
				</nav>
				<br/><br/>
			</header>
		<!-- Main -->
			<section id="main" class="wrapper">
				<div class="container">
					<header class="major">
					<!-- Image du profil et information de la personne-->
								<section id="two" class="wrapper style2 special">
									<div class="container">
										<section class="profiles">
											<div class="row">
												<section class="3u 6u(medium) 12u$(xsmall) profile">
													<!-- <img src="images/profile_placeholder.gif" alt="" /> -->
													<!--Si c'est un homme alors ça sera l'image d'homme qui sera affiché si c'est une femme alors
												immage d'une femme-->
												 <?php

													 	$bdd      = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
													 	$login    = $_SESSION['login'];
								                        $reponse  = $bdd->query("SELECT * FROM utilisateur WHERE adresseMail = '$login'");//requête pour tester l'authenticité des log de connexion
								                        $donnee   = $reponse->fetch();
								                       
														$id=$donnee['IDUser'];

								                        $reqPlace = $bdd->query("SELECT NumeroPlaces, NumeroAllee FROM places_parking WHERE IDUser = '$id'");
								                        $place    = $reqPlace->fetch();
								                       if($donnee['Sexe']==0)
								                        {
													 ?>
													 <img src="images/F.jpg" width="92" height="92" alt="" /><?php 
														} 
														else
														{
													 ?>
													 <img src="images/M.jpg" width="92" height="92" alt="" />
													 <?php } ?>

													
													<b>
														<p>nom    : <?php echo $donnee['Nom'];?></p>
														<p>prenom : <?php echo $donnee['Prenom'];?></p></b>
													<br/><br/>
													<p>Adresse mail : <?php echo $donnee['AdresseMail'];?></p>
													<p>Adresse : <?php echo $donnee['AdressePostal'];?></p>
													<p>Code Postal : <?php echo $donnee['CodePostal'];?></p>
													<p>Ville : <?php echo $donnee['Ville'];?></p>
													<p>Immatriculation : <?php echo $donnee['Immatriculation'];?></p>
													<p>Place : <?php if($donnee['IsConfirme']==1) { ?>OUI <?php } else { ?>NON<?php }?></p>
													<p>Votre place : <?php echo "numero : ". $place['NumeroPlaces']. " Allée : ".$place['NumeroAllee'];?></p>
													<ul class="actions">
														<li>
															<a href="modification.php" class="button big">Modifier compte</a>
														</li>
													</ul>
												</section>
											</div>
										</section>
									</div>
								</section>
							</header>
							<section id="one" class="wrapper style1 special">
											<div class="container">
												<div class="row 150%">
													<div class="4u 12u$(medium)">
														<a class="tool" href="#demande"><section class="box">
															<i class="icon big rounded color1 fa-car"></i>
															<h3>Faire une demande de place.</h3>
															<p><u>Faire une demande de place : </u> Vous avez une voiture et vous voulez accéder au parking de l'entreprise ? Faites votre demande ici, celle-ci sera validée ultérieurement par l'administrateur qui s'occupe du parking.</p>
														</section></a>
													</div>
													<div class="4u$ 12u$(medium)">
														<a class="tool" href="#def"><section class="box">
															<i class="icon big rounded color6 fa-sort-amount-asc"></i>
															<h3>Rendre une place définitivement</h3>
															<p><u>Rendre sa place définitivement :</u> Lorsque vous voulez rendre votre placé définitivement pour plusieurs motifs comme démission, licenciement, mutation ou bien que vous ne vous servez plus de votre voiture. Pensez à rendre votre place.</p>
														</section></a>
													</div>
												</div>
											</div>
										</section>

<!-- Scroll pour accéder en bas de page au lieu de faire une redirection sur une autre page.-->
										<div id="demande">
											<section id="two" class="wrapper style2 special">
												<div class="container">
													<header class="major">
														
														<?php 
															if(isset($_POST["bout1"]))//quand on appui sur le bouton valider
		 													{
		 														require('fonction.php');
		 													 	demandePlace($id);
		 														
		 													}
		 												if($donnee['IsConfirme']==0)
		 												{
														?>
														<h2>Faire une demande de place</h2>
														<form action="acc_user.php" method="post">
															<input type="submit" value="Demande" class="button big" name="bout1" />
														</form>
														<?php }?>
														</header>
										</div>
									</section>
								</div>

								

							<div id="def">
								<section id="two" class="wrapper style2 special">
									<div class="container">
										<header class="major">
											<h2>Rendez votre place définitivement</h2>
											<?php 
															if(isset($_POST["bout2"]))//quand on appui sur le bouton valider
		 													{
		 														
		 													 	rendrePlace($id);
		 														
		 													}
		 												if($donnee['IsConfirme']==0)
		 												{
														?>
														
														<form action="acc_user.php" method="post">
															<input type="submit" value="Rendre" class="button big" name="bout2" />
														</form>
														<?php }?>
											</header>
							</div>
						</section>
						</div>
	</body>
</html>
