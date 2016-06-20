<!DOCTYPE html>
<?php
  	session_start(); 
	/*$_SESSION['connect']=0;*/
	$ID = $_SESSION['connect'];


?> 
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>GestPark</title>
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
	<body class="landing">

		<!-- Header -->
			<header id="header">
				<h1><a href="index.html">Gestion Parking</a></h1>
				<nav id="nav">
					<ul>
						<li><a href="index.php">Acceuil</a></li>
						<li><a href="generic.html">Generic</a></li>
						<li><a href="elements.html">Elements</a></li>
					</ul>
				</nav>
			</header>

		<!-- Banner -->
			<section id="banner">
				<h2>Inscription</h2>
				<p>Veuillez remplir les champs ci dessous pour vous inscrire</p>

				<?php 
				//echo $_SESSION['connect'];
				if ($_SESSION['connect']==1)
				{
				?>
				<ul class="actions">
					<li>
						<a href="#" class="button big">Mes demandes</a>
						
					</li>
				</ul>
			</section>
				<?php } ?>


		<!-- One -->
			<section id="one" class="wrapper style1 special">
				<div class="container">
					<header class="major">
						<h2></h2>
						<p></p>
					</header> 
				<div id="form_inscription">
					<form action="recup_inscription.php" method="post">

						 <div class="wrap"> Homme  <input type="checkbox" id="sexe1" name="sexe1" value="1"/><label class="slider-v3" for="sexe1"></label>
						Femme  <input type="checkbox" id="sexe" name="sexe" value="0"/><label class="slider-v3" for="sexe"></label>
						<br />
						<br />
						<label for="field2"><span> <span class="required"></span></span><input type="text" class="input-field" name="nom" value="" required placeholder="nom"/></label>
						<br />
						<label for="field2"><span><span class="required"></span></span><input type="text" class="input-field" name="prenom" value="" required placeholder="prénom"/></label>
						<br />
						<label for="field2"><span> <span class="required"></span></span><input type="date" class="input-field" name="dateNaissance" value="" required placeholder="Date de Naissance"/></label>
						<br />
						<label for="field2"><span><span class="required"></span></span><input type="text" class="input-field" name="adresse" value="" required placeholder="adresse"/></label>
						<br />
						<label for="field2"><span><span class="required"></span></span><input type="text" class="input-field" name="cp" value="" required placeholder="CP"/></label> <label for="field2"><span><span class="required"></span></span><input type="text" class="input-field" name="ville" value="" required placeholder="ville"/></label>
						<br />
						<label for="field2"><span><span class="required"></span></span><input type="text" class="input-field" name="immatriculation" value="" required placeholder="immatriculation"/></label>
						<br />
						<label for="field2"><span><span class="required"></span></span><input type="email" class="input-field" name="adresseMail" value="" required placeholder="adresse eMail"/></label>
						<br />
						<label for="field2"><span><span class="required"></span></span><input type="password" class="input-field" name="mdp" value="" required placeholder="mot de passe"/></label><br />
						<br />
						<label for="field2"><span><span class="required"></span></span><input type="password" class="input-field" name="mdpConfirmation" value="" required placeholder="confirmer le mot de passe"/></label>
						<br />
						<br />
						<br />
						<label><span>&nbsp;</span><input type="submit" value="S'inscrire" /> 
						<span>&nbsp;</span><input type="reset" value="Annuler" /></label>

					</form>	
							
				</div>
					<br />
		  			<br />
		  			<br />
			</section>

		<!-- Two -->
			<section id="two" class="wrapper style2 special">
				<div class="container">
					<header class="major">
						<h2>Gestion Parking</h2>
						

	</body>
</html>