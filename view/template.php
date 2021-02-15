<?php 
if(!isset($_SESSION))
{
	session_start();	
}
$hidden="";
if($_SESSION['infosUser']['role']==1)
{
	$hidden="hidden";
}
$disabled='disabled';
if(isset($_SESSION['infosAgent']))
{
	$disabled='';
}

if(isset($codePage))
{
	switch ($codePage) {
		case 1:
			$active1="active";
			break;
		case 2:
			$active2="active";
			break;

		case 3:
			$active3="active";
			break;

		default:
			$active1="active";
			break;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?= $title ?></title>
	<meta charset="utf-8">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	<link type="text/css" rel="stylesheet" href="./style/style.css"/>
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
	<link href='fullcalendar//lib/main.css' rel='stylesheet' />
    <script src='fullcalendar/lib/main.js'></script>
</head>
<body>
	<div  class="container">
		<div class="row nav-margin">
			<div class="col-md-6">
				<ul class="nav nav-pills">
  					<li class="nav-item">
   					<a class="nav-link <?=$active1?> " aria-current="page" href="index.php">Accueil</a>
  					</li>
 					<li class="nav-item  dropdown">
    					<a class="nav-link <?=$active2?> dropdown-toggle" data-bs-toggle="dropdown" href="" role="button" aria-expanded="false">Planning</a>
   						<ul class="dropdown-menu">
   							<li><a class="dropdown-item" href="index.php?page=planningView">Voir Planning</a></li>
      						<li <?=$hidden?>><a class="dropdown-item" href="index.php?page=planningCreate">Créer Planning</a></li>
     						<li <?=$hidden?>><a class="dropdown-item" href="index.php?page=planningEdit">Modifier Planning</a></li>
     						<li hidden="hidden"><a class="dropdown-item" href="index.php?page=planningParam">Paramétrer Planning</a></li>
    					</ul>
  					</li>
 					<li class="nav-item">
    					<a class="nav-link <?=$active3?> dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Agent</a>
    					<ul class="dropdown-menu">
      						<li><a class="dropdown-item" href="index.php?page=infosAgent">Infos Agent</a></li>
     						<li><a class="dropdown-item" href="index.php?page=etatHeures">Etat des Heures</a></li>
      						<li <?=$hidden?>><hr class="dropdown-divider"></li>
      						<li <?=$hidden?>><a class="dropdown-item <?=$disabled?>" href="index.php?action=switchAgent">Changer Agent</a></li>
    					</ul>
  					</li>
  					<li class="nav-item">
    					<a class="nav-link " href="index.php?deconnexion=true" tabindex="-1" aria-disabled="true">Déconnexion</a>
  					</li>
  				</ul>
  			</div>
  			<div class="col-md-6 compte-user-infos">
  			<ul class="nav nav-pills">
  				<li class="nav-item">
  				Bonjour <?=$_SESSION['infosUser']['prenom'].' '.$_SESSION['infosUser']['nom'];?>
 				<?php
				if($_SESSION['infosUser']['role']==2)
				{
				?>	
					<span class="badge bg-light text-dark">Administrateur</span>
				<?php
				}
				?> 
				<p <?=$hidden?>>Agent en cours de gestion: 
				<?php  
				if(isset($_SESSION['infosAgent']))
				{
				?>
    			<a href="#" id="agentManage"><?=$_SESSION['infosAgent']['prenom'].' '.$_SESSION['infosAgent']['nom'];?></a>.</p>
				<?php
				}else
				{
				?>
				aucun.</p>
				<?php
				}
				?>
				</li>

			</ul>
  		</div>
  	<?=$content?>
	</div>

</body>
</html>