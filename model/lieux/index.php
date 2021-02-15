<?php
$db = new PDO('mysql:host=localhost;dbname=Gestion_Agents','root','root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
session_start();
function chargerClasse($classname)
{
	require $classname.'.php';
}
spl_autoload_register('chargerClasse');
$manager = new LieuxManager($db);
if(isset($_POST['creer']))
{	
	$donnees=['lieu'=>$_POST['lieu'],
	'adresse'=>$_POST['adresse'],
	'tel'=>$_POST['tel']];

	$lieux= new Lieux($donnees);
	if($manager->add($lieux))
	{
		echo "Nouveau lieu ajouté. ";
	}
	else
	{
		echo 'Erreur lors de l\'ajout';
	}
}
elseif(isset($_POST['modifier']))
{
	$lieux=new Lieux($_SESSION['lieux']);
	?>
	<form action='' method="post">
		<input type="hidden" name="id" value="<?=$lieux->id()?>">
		<label>Nom</label>
		<input type="text" name="lieu" value="<?=$lieux->lieu()?>">
		<label>Adresse</label>
		<input type="text" name="adresse" value="<?=$lieux->adresse()?>">
		<label>Numéro de téléphone</label>
		<input type="text" name="tel" value="<?=$lieux->tel()?>">	
		<button type="submit" name="update">Mettre à jour</button>	
	</form>
	<?php
}
elseif(isset($_POST['update']))
{
	$donnees=['id'=>$_POST['id'], 'lieu'=>$_POST['lieu'], 'adresse'=>$_POST['adresse'], 'tel'=>$_POST['tel']];
	$lieux=new Lieux($donnees);

	if($manager->update($lieux))
	{
		echo 'Lieu mis à jour. ';
	}
	else
	{
		echo 'Erreur lors de la mis à jour.';
	}

}
elseif(isset($_POST['supprimer']))
{
	$lieux= new Lieux($_SESSION['lieux']);
	$manager = new LieuxManager($db);
	if($manager->delete($lieux))
	{
		echo "Lieux supprimé. ";
	}
	else
	{
		echo "Erreur lors de la suppression. ";
	}
}
elseif(isset($_POST['trouver']))
{
	$lieux=$manager->get($_POST['lieu']);
	$_SESSION['lieux']=['id'=>$lieux->id(), 'lieu'=>$lieux->lieu(), 'adresse'=>$lieux->adresse(), 'tel'=>$lieux->tel()];

	echo "Que souhaitez-vous faire du lieux: ".$lieux->lieu();
	?>
	<form action="" method="post">
	<button type= "submit" name="modifier">Modifier</button>
	<button type= "submit" name="supprimer">Supprimer</button>
	</form>
	<?php
}
else
{
//$liste=$manager->getList();

?>

<form action="" method="post"> 
	<h1>Création du lieu</h1>
	<label>Nom</label>
	<input type="text" name="lieu">
	<label>Adresse</label>
	<input type="text" name="adresse">
	<label>Numéro de téléphone</label>
	<input type="text" name="tel">
	<button type= "submit" name="creer">Créer</button>
	<button type= "submit" name="trouver">Trouver</button>
</form>

<?php  
}
?>