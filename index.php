<!DOCTYPE html>
<?php
  	session_start(); // pour les variables de session
	$_SESSION['connect']=0; //on initialise la variable "connect" à 0
	if(isset($_POST["bouton"]))//quand on appui sur le bouton valider
		 	{
				$login = $_POST["login"]; //on récupère le login du formulaire
				$code = $_POST["code"]; // idem pour le code
				
			    try
                {
                	    $_SESSION['login'] = $_POST['login'] ; // on aura besoin de la variable login dans le reste du code 
                        $bdd = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
                        $reponse = $bdd->query("SELECT MDP,IDRole FROM utilisateur WHERE adresseMail = '$login'");//requête pour tester l'authenticité des log de connexion
                        $donnee=$reponse->fetch();
             			
             			$_SESSION['role'] = $donnee['IDRole'];
             			$_SESSION['login']= $login;
             			  
                        if($code!=$donnee['MDP'] or $code=="")// si le mot de passe, variable de session "connect" est faux ou nul
						{
							$_SESSION['connect']=0;
							$erreur="Erreur, réessayez"; // on affiche un message d'erreur 
						}  
						else
						{
							$_SESSION['connect']=1;// on met la variable de session : "connect" à vrai pour acceder aux autres pages
							
							if($donnee['IDRole']==1)
							{
								header(("location:admin.php"));// redirection de l'utilisateur vers l'acceuil
							}
							else  
							{
								header(("location:acc_user.php"));// redirection de l'utilisateur vers l'acceuil
							}	
						} 
                }
                catch(Exception $e)
                {
                    echo 'échec de connection à la base de données';
                    exit();
                } 
			}
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
				<h1><a href="acceuil.php">Gestion Parking</a></h1>
				<nav id="nav">
					<ul>
						<li><a href="acceuil.php">Acceuil</a></li>
						<li><a href="#">Generic</a></li>
						<li><a href="inscription.php">S'inscrire</a></li>					
					</ul>
				</nav>
			</header>

		<!-- Banner -->
			<section id="banner">
				<h2>Bienvenue</h2>
				<p>Ici la plateforme de gestion des places de parking.</p>

				
		<!-- One -->
			<section id="one" class="wrapper style1 special">
				<div class="container">
					<header class="major">
						<h2></h2>
						<p></p>
					</header>
					<div id="login">
						<center>			
							<form action="index.php" method="post" id="login">
							<?php if(isset($erreur)) echo "$erreur"; ?>
					       <label for="login" >Login :</label><input type="email" name= "login" id="cLog" /><br />
					       <br />
					       <label for="code" >Mot de passe :</label><input type="password" name= "code" id="cLog" /><br />
					       <a href="inscription.php"> S'inscrire </a> <a href="inscription.php"> Mot de passe oublié </a>
						   <br />
						   <br />
						   <label><span>&nbsp;</span><input type="submit" value="Valider" name="bouton" /> 
						</center>	
					</div>
					</div>
					<br />
		  			<br />
		  			<br />
		  			<div id="fenetre">
					<div class="row 150%">
						<div class="4u 12u$(medium)">
							<section class="box">
								<i class="icon big rounded color1 fa-cloud"></i>
								<h3>Regle 1</h3>
								<p>explication des regles pour les places de parking</p>
							</section>
						</div>
						<div class="4u 12u$(medium)">
							<section class="box">
								<i class="icon big rounded color9 fa-desktop"></i>
								<h3>regle 2</h3>
								<p>explication des regles pour les places de parking</p>
							</section>
						</div>
						<div class="4u$ 12u$(medium)">
							<section class="box">
								<i class="icon big rounded color6 fa-rocket"></i>
								<h3>regle 3</h3>
								<p>explication des regles pour les places de parking</p>
							</section>
						</div>
					</div>
				</div>

			</section>

		<!-- Two -->
			<section id="two" class="wrapper style2 special">
				<div class="container">
					<header class="major">
						<h2>Gestion Parking</h2>
						

	</body>
</html>