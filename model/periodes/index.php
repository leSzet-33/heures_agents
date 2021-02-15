<?php

require "periode.php";
require "periodeManager.php";
$db = new PDO('mysql:host=localhost;dbname=Gestion_Agents','root','root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$periodeManager= new periodeManager($db);
if(isset($_POST['ajouter']))
{
	$donnees=[
	'nomPeriode'=>$_POST['nom_periode'],
	'dateDebut'=>$_POST['debut'],
	'dateFin'=>$_POST['fin'],
	'typePeriode'=>$_POST['type']
	];

	$periode = new Periode($donnees);
	if($periodeManager->add($periode))
	{
		echo "<p>Nouvelle Période ajoutée. <p>";
		var_dump($periode);
	

	}else
	{
		var_dump($donnees);
	}
}
  ?>



<h2>Paramétrage périodes</h2>

<form action="" method="post">
	
	Nom de la période
	<input type="text" name="nom_periode">
	Début de la période
	<input type="date" name="debut">
	Fin période
	<input type="date" name="fin">
	<select name="type">
		<option value="periscolaire">Périscolaire</option>
		<option value="vacances">Vacances</option>
	</select>
	<button type="submit" name="ajouter">Ajouter</button>

</form>