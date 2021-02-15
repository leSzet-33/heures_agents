
<?php
session_start();
function chargerClasse($classname)
{
	require $classname.'.php';
}
spl_autoload_register('chargerClasse');

if(isset($_POST['creer']))

{
	$donnees=['nom'=>$_POST['nom'],
	'prenom'=>$_POST['prenom'],
	'pass'=>$_POST['pass'],
	'mail'=>$_POST['mail'],
	'poste'=>$_POST['poste']];

	$agent = new Agent($donnees);

	$db = new PDO('mysql:host=localhost;dbname=Gestion_Agents','root','root');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$manager=new AgentManager($db);

	if($manager->add($agent))
	{
		echo "Agent ajouté(e) à la base de données.";
	}
	else
	{
		echo "Erreur...";

	}



}
elseif(isset($_POST['supprimer']))
{
	
	
	$agent= new Agent($_SESSION['agent']);
	$db = new PDO('mysql:host=localhost;dbname=Gestion_Agents','root','root');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$manager=new AgentManager($db);
	if($manager->delete($agent))
	{
		echo'Agent supprimé.';
	}
	else
	{
		echo 'Erreur...';
	}
}
elseif(isset($_POST['modifier']))
{
	$agent= new Agent($_SESSION['agent']);
	?>
	<form action="" method="post">
		<input type="hidden" name="id" value="<?=$agent->id()?>">
		<label>Nom</label>
		<input type="text" name="nom" value="<?=$agent->nom()?>">
		<label>Prenom</label>
		<input type="text" name="prenom" value="<?=$agent->prenom()?>">
		<label>Mot de passe</label>
		<input type="password" name="pass" value="<?=$agent->pass()?>">
		<label>Mail</label>
		<input type="mail" name="mail" value="<?=$agent->mail()?>">
		<label>Poste Occupée</label>
		<input type="text" name="poste" value="<?=$agent->poste()?>">
		<button type= "submit" name="update">Modifier</button>
	</form>
	<?php 
	
}
elseif(isset($_POST['update']))
{
	$_SESSION['agent']=["id"=>$_POST['id'],"nom"=>$_POST['nom'], "prenom"=>$_POST['prenom'], "pass"=>$_POST['pass'], "mail"=>$_POST['mail'], "poste"=>$_POST['poste']];
	$agent= new Agent($_SESSION['agent']);
	$db = new PDO('mysql:host=localhost;dbname=Gestion_Agents','root','root');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$manager=new AgentManager($db);
	if($manager->update($agent))
	{
		echo "Agent modifié.";
	}
	else
	{
		echo 'Erreur';
	}
}
elseif(isset($_POST['trouver']))
{
	
	$db = new PDO('mysql:host=localhost;dbname=Gestion_Agents','root','root');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$manager=new AgentManager($db);
	$agent=$manager->get($_POST['nom'],$_POST['prenom']);


	$_SESSION['agent']=["id"=>$agent->id(),"nom"=>$agent->nom(), "prenom"=>$agent->prenom(), "pass"=>$agent->pass(), "mail"=>$agent->mail(), "poste"=>$agent->poste()];
	echo "Souhaitez vous supprimer l'agent ".$agent->nom().' '.$agent->prenom().'? ';
	?>
	<form action='' method='post'>
	<button type= "submit" name="modifier">Modifier</button>
	<button type= "submit" name="supprimer">Supprimer</button>
	<button type= "submit" name="">Non</button>
	</form>
	<?php
}
else
{
?>
<form action="" method="post">
	
	<label>Nom</label>
	<input type="text" name="nom">
	<label>Prenom</label>
	<input type="text" name="prenom">
	<label>Mot de passe</label>
	<input type="password" name="pass">
	<label>Mail</label>
	<input type="mail" name="mail">
	<label>Poste Occupée</label>
	<input type="text" name="poste">
	<button type= "submit" name="creer">Créer</button>
	<button type= "submit" name="trouver">Trouver</button>
</form>
<?php
}

?>