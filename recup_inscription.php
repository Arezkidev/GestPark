<?php
        $nom              = $_POST ["nom"]; // on récupere les données du formulaire
        $prenom           = $_POST ["prenom"];
        $date             = $_POST ["dateNaissance"];
        $adr              = $_POST ["adresse"];
        $cp               = $_POST ["cp"];
        $ville            = $_POST ["ville"];
        $immatriculation  = $_POST ["immatriculation"];
        $mail             = $_POST ["adresseMail"];
        $mdp              = $_POST ["mdp"];
        $mdpConfirmation  = $_POST ["mdpConfirmation"];
        if(isset($_POST["sexe1"] ))
        {
            $sexe         = 1;
        }
        else if(isset($_POST["sexe"]))
        {
            $sexe         = 0;
        }

        $date = new DateTime($date);      
        $date= $date->format('Y-m-d');
        

    	try
        {
            $bdd     = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
            $bdd->exec("INSERT INTO `parking`.`utilisateur` (`Nom`, `Prenom`, `DateNaissance`, `Sexe`, `AdresseMail`, `MDP`, `AdressePostal`, `Ville`, `CodePostal`, `IDRole`, `IsHandicape`, `IsConfirme`, `Immatriculation`, `Priorite`) 
                        VALUES ('$nom', '$prenom', '$date', '$sexe', '$mail', '$mdp', '$adr', '$ville', '$cp', '2', '0', '0', '$immatriculation', '1');"); //on insere les données dans la table
            header('Location: index.php'); //on fait une redirection vers la page d'ajout 
            exit(); //on quitte cette page
        
        }
        catch(Exception $e)
        {
            echo 'Echec de la connexion à la base de données';
            exit();
        }
        

	?>   
