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
       
if ($connect == "1") // Si le visiteur s'est identifié.
{
// On affiche la page cachée.
?> 
<!DOCTYPE html>
<html dir="ltr">
<head>
     <div id="deco">
      <a href="deco.php?>">Deconnexion</a> 
    </div>
    <?php
        try
        {

                // on récupère l'id voir commentaire dans le tableau
             $_SESSION['id'] = $id ; // on utilise une variable session par besoin
             $bdd = new PDO('mysql:host=localhost;dbname=gestion_formation', 'root', '');
             $donnees = $bdd->query("SELECT * FROM formation WHERE id_formation = '$id'"); // requete pour récuperer la ligne de la bdd
             $ligne = $donnees->fetch();
             $description = $ligne['description_formation'];//récupération des saisies
             $adresse = $ligne['adresse_formation'];
             $ville = $ligne['ville_formation'];
             $cout = $ligne['cout_formation'];
             $nbPlace = $ligne['place_disponible'];
             $debut = $ligne['debut_formation'];
             $fin = $ligne['fin_formation'];

     //Tu recuperes l'id du contact
}
        catch(Exception $e)
        {
            echo 'Echec de la connexion à la base de données';
            exit();
        }
   ?>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0">
	<title>Gestion Formation</title>
		<!-- Start css3menu.com HEAD section -->
	<link rel="stylesheet" href="styles/style.css" type="text/css" /><style type="text/css">._css3m{display:none}</style>
	<!-- End css3menu.com HEAD section -->

	
</head>
<body ontouchstart="" style="background-color:#EBEBEB">
<!-- Start css3menu.com BODY section -->
<input type="checkbox" id="css3menu-switcher" class="c3m-switch-input">
<ul id="css3menu1" class="topmenu">
    <li class="switch"><label onclick="" for="css3menu-switcher"></label></li>
    <li class="topfirst"><a href="acceuil.php" style="width:188px;height:57px;line-height:57px;">Acceuil</a></li>
    <li class="topmenu"><a href="ajout.php" style="width:188px;height:57px;line-height:57px;">Ajout Formation</a></li>
    <li class="topmenu"><a href="modif.php" style="width:188px;height:57px;line-height:57px;">Modification Formation</a></li>
</ul><p class="_css3m"><a href="http://css3menu.com/">css3 button generator</a> by Css3Menu.com</p>
<!-- End css3menu.com BODY section -->

        <div id="corpAjout">
            <H1>Ajout Formation</H1>
        <H2>Formation</H2>
        <hr widht="50%" color="orange" size="3"/>  <!-- barre horizontale -->
        <br/>
        
<div class="form-style-2">

<div class="form-style-2-heading"></div>
<form action="recup_modif.php" method="post">
<label for="field1"><span>Desciption de la formation <span class="required">*</span></span><input type="text" class="input-field" name="description" value="<?php echo $description ?>" requierd /></label>
<br />
<label for="field2"><span>Adresse de la formation <span class="required">*</span></span><input type="text" class="input-field" name="adresse" value="<?php echo $adresse ?>" requierd/></label>
<br />
<label for="field2"><span>Ville de la formation <span class="required">*</span></span><input type="text" class="input-field" name="ville" value="<?php echo $ville ?>" requierd/></label>
<br />
<label for="field2"><span>Coût de la formation <span class="required">*</span></span><input type="double" class="input-field" name="cout" value="<?php echo $cout ?>"requierd/></label>
<br />
<label for="field2"><span>Nombre de place <span class="required">*</span></span><input type="int" class="input-field" name="nbPlace" value="<?php echo $nbPlace ?>" requierd /></label>
<br />
<label for="field2"><span>Date de debut <span class="required">*</span></span><input type="date" class="input-field" name="debut" value="<?php echo $debut ?>" requierd/></label>
<br />
<label for="field2"><span>Date de fin <span class="required">*</span></span><input type="date" class="input-field" name="fin" value="<?php echo $fin ?>" requierd/></label>
<br />
<br />
<br />
<br />
<label><span>&nbsp;</span><input type="submit" value="Submit" /> 
<span>&nbsp;</span><input type="reset" value="Annuler" /></label>



</form>
</div>  

<body ontouchstart="" style="background-color:#EBEBEB">
<!-- Start css3menu.com BODY section -->
    
        </div>

</body>
</html>
<?php 
}
else
{
   header('Location: index.php');
   exit();
}