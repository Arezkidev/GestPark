<?php
$pdo = new PDO('mysql:host=SRV-WAKANDA.cloudapp.net;port=13306;dbname=parking', 'devSlam', '!Slam2016');

if(isset($_POST['btn_att'])){ //recherche utilisateur dans liste attente
	$rch = $_POST['rch_att'];

	$req3 = $pdo->query("SELECT IDuser FROM demandes_places
	WHERE IDUser = (SELECT * FROM utilisateur
									WHERE Nom LIKE '%$rch%' OR Prenom LIKE '%$rch%' OR DateNaissance LIKE '%$rch%'
									OR AdresseMail LIKE '%$rch%' OR AdressePostal LIKE '%$rch%' OR CodePostal LIKE '%$rch%' OR Immatriculation LIKE '%$rch%')");
	$res3 = $req3->fetch();
}
 ?>
<section id="two" class="wrapper style2 special">
  <div class="container">
    <header class="major">
      <h2>Liste d'Attente</h2>
      <p>Vous pouvez consulter la <a href="#">Liste</a> d'Attente, ou <a href="#">Rechercher</a>,
        un Utilisateur en Attente.</p>
    </header>
    <form>
      <input type="text" name="rch_att" placeholder="Rechercher un utilisateur..."/><br/>
      <input type="submit" name="btn_att" value="Recherche"/>
    </form>
    <?php
    if(!empty($res3)){
      echo "<table><tr>
              <th>Nom</th><th>Prénom</th><th>CP</th><th>Immatriculation</th><th>Mail</th><th>Handicape</th></tr>";
      while($res3){
        echo "<tr><td>"
        .$res3['Nom']."</td><td>"
        .$res3['Prenom']."</td><td>"
        .$res3['CP']."</td><td>"
        .$res3['Immatriculation']."</td><td>"
        .$res3['Mail']."</td><td>"
        .$res3['IsHandicape']."</td></tr>";
      }
      echo "</table>";
    }
    ?>
  </div>
</section>
<section id="one" class="wrapper style1 special">
	<div class="container">
	</div>
</section>
