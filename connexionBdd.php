<?php
	try
	{
		   $bdd = new PDO('mysql:host=SRV-WAKANDA.cloudapp.net:13306;dbname=parking', 'devSlam', '!Slam2016');
		 
		
		 $reponse = $bdd->query("SELECT * FROM utilisateur ");
		    $donnee=$reponse->fetch();
		    echo $donnee['Nom'];
		   
         
	}
	catch(Exception $e)
	{
	        echo 'échec de connection à la base de données';
	        exit();
	} 
?>