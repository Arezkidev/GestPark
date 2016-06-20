<?php
/* AREZKI */
function totalDemande()
{
  $pdo             = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
  $reqNbDemande      = $pdo->query("SELECT count(IDDemandes) as nb FROM demandes_places WHERE IDPlace IS NULL");
  $nbDemande        = $reqNbDemande->fetch();
  echo $nbDemande ['nb'];
}


function placeTotal() 
{
  $pdo             = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
  $reqNbPlace      = $pdo->query("SELECT count(IDPlace) as nb FROM places_parking ");
  $nbPlace         = $reqNbPlace->fetch();
  echo $nbPlace['nb'];
}

function demandePlace($id)
{
  $date= date("Y-m-d");
  $login = $_SESSION['login'];
  $bdd = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
  $bdd->exec("INSERT INTO `parking`.`demandes_places` (`IDUser`, `IDPlace`, `Statut`, `DateDemande`, `DateCloture`) 
                        VALUES ('$id', NULL, '1', '$date', NULL);");
} 

 

function attribuerPlace($id)
{
  $bdd     = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
  $res     = $bdd->query("SELECT * FROM places_parking where IDUser is NULL");
  $donnees = $res->fetch(); 
  $place   = $donnees['IDPlace'];

 $bdd->exec("UPDATE places_parking SET IDUser = '$id' WHERE IDPlace = '$place'");
 $bdd->exec("UPDATE demandes_places SET IDPlace = '$place' WHERE IDUser = '$id'");
 $bdd->exec("UPDATE utilisateur SET IsConfirme = '1' WHERE IDUser = '$id'"); 
}


function placeDispo()
{
  $pdo             = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
  $reqNbPlaceDispo = $pdo->query("SELECT count(IDPlace) as nb FROM places_parking where IDUser is NULL");//requête pour tester l'authenticité des log de connexion
  $nbPlaceDispo    = $reqNbPlaceDispo->fetch();
  echo $nbPlaceDispo['nb'];
}

function rendrePlace()
{
     $pdo   = new PDO('mysql:host=localhost;dbname=parking', 'root', ''); 
     
}

/* GESTION DES PLACES - LUDWIG */


function searchP($val){
  $pdo = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
  $req = $pdo->query("SELECT * FROM places_parking WHERE NumeroPlaces LIKE '%$val%' OR NumeroAllee LIKE '%$val%'");

  echo '<table><tr><th>Numéro</th><th>Allée</th><th>Occupée</th></tr>';

  while($res = $req->fetch()){
    echo '<tr><td>'.$res['NumeroPlaces'].'</td><td>'.$res['NumeroAllee'].'</td>';
    if($res['IDUser'] == NULL){
      echo '<td>Non</td></tr>';
    }
    elseif($res['IDUser'] != NULL){
      echo '<td>Oui</td></tr>';
    }
  }
  echo '</table>';
  $pdo = NULL;
}

function listOccupees(){
  $pdo = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
  $req = $pdo->query("SELECT * FROM places_parking WHERE IDUser IS NOT NULL");

  echo '<table><tr><th>Numéro</th><th>Allée</th><th>Nom</th><th>Immatriculation</th><th>Action</th></tr>';

  while($res = $req->fetch()){
    $place = $res['IDPlace'];
    $user = $res['IDUser'];
    $req1 = $pdo->query("SELECT Nom, Immatriculation FROM utilisateur WHERE IDUser = $user");
    $res1 = $req1->fetch();

    echo '<tr><td>'
    .$res['NumeroPlaces'].'</td><td>'
    .$res['NumeroAllee'].'</td><td>'
    .$res1['Nom'].'</td><td>'
    .$res1['Immatriculation'].'</td><td><a href="admin.php?action=lib&place='.$place.'" class="button">Libérer</td></tr>';
  }
  echo '</table>';
  $pdo = NULL;
}

function listLibres(){
  $pdo = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
  $req = $pdo->query("SELECT * FROM places_parking WHERE IDUser IS NULL");

  echo '<table><tr><th>Numéro</th><th>Allée</th><th colspan=3>Attribution</th><th>IDPlace</tr>';

  while($res = $req->fetch()){
    echo '<tr><td>'
    .$res['NumeroPlaces'].'</td><td>'
    .$res['NumeroAllee'].'</td><td>
    <form action="updatePlaceLib('.$res['IDPlace'].')">
        <td><input type="text" name="attribuer" placeholder="Immatriculation"/></td>
        <td><input type="submit" name="btn_attribuer" value="Attribuer"/></td>
        </form>';
    echo '<td>'.$res['IDPlace'].'</td></tr>';
  }
  echo '</table>';
  $pdo = NULL;
}

function updatePlaceLib($IDPlace){
  $pdo = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
  $immat = $_POST['attribuer'];

  $req = $pdo->query("SELECT IDUser FROM utilisateur WHERE Immatriculation = '.$immat.' ");
  $res = $req->fetch(0);

  $req1 = $pdo->query("UPDATE places_parking SET IDUser = $res");
  $pdo = NULL;
}

function updatePlaceOcc($ID){
  $pdo = new PDO('mysql:host=localhost;dbname=parking', 'root', '');
  $req = $pdo->query("UPDATE places_parking SET IDUser = NULL WHERE IDPlace = $ID");
  $pdo = NULL;
}
?>
