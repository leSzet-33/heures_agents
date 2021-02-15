<?php
// CONTROLLER
require_once ('./model/agent/agent.php');
require_once ('./model/agent/agentManager.php');
require_once ('./model/heures/heuresPrevues.php');
require_once ('./model/heures/heuresPrevuesManager.php');
require_once ('./model/heures/heuresReelles.php');
require_once ('./model/heures/heuresReellesManager.php');
require_once ('./model/periodes/periodeManager.php');
require_once ('./model/periodes/periode.php');
require_once ('./model/periodes/periodeManager.php');
require_once ('./model/taches/tacheManager.php');
require_once ('./model/taches/taches.php');
require_once ('./model/lieux/lieuxManager.php');
require_once ('./model/lieux/lieux.php');
require_once ('./model/types/typesManager.php');
require_once ('./model/types/types.php');
require_once ('./model/postes/postesManager.php');
require_once ('./model/postes/postes.php');
require_once ('./model/nature/natureManager.php');
require_once ('./model/nature/nature.php');
require_once ("./install/connect.php");


if(!isset($_SESSION))
{
    session_start();
}

function getLists()
{
	$db=connect_db();
		 // je récupère les données relatives à la période
		 
		 $periode="scolaire";
		 $managerPeriode=new PeriodeManager($db);
		 $listePerisco=$managerPeriode->getList($periode);

		 // je récupère les données relatives aux tâches

		 $managerTache = new TacheManager($db);
		 $listeTache=$managerTache->getList();

		 // je récupère les données relatives au lieux

		 $managerLieux = new LieuxManager($db);
		 $listeLieux=$managerLieux->getList();

		 // je récupère les données relatives au poste
		 $managerPoste = new PosteManager($db);
		 $listePostes=$managerPoste->getList();

		 return $infos=[
		 	'listePerisco'=>$listePerisco,
		 	'listeTache'=>$listeTache,
		 	'listeLieux'=>$listeLieux,
		 	'listePoste'=>$listePostes
		 ];
}

function planningCreatePage()
{
	$lists=getLists();
	require('./view/createPlanningView.php');
}

function addPlanning($mail, $periode, $dateDebut, $dateFin, $hdebut, $hfin, $type, $tache, $lieu, $poste, $jours)
{
	$db=connect_db();
	$managerLieux=new LieuxManager($db);
	$managerTache=new TacheManager($db);
	$managerType=new TypeManager($db);
	$managerPeriode=new PeriodeManager($db);
	$managerHeuresPrevues=new heuresPrevuesManager($db);
	$managerAgent=new agentManager($db);
	$managerPoste = new PosteManager($db);

	$agent=$managerAgent->get($mail);
	$lieux= $managerLieux->get($lieu);
	$tache= $managerTache->get($tache);
	$type= $managerType->get($type);
	$periodes=$managerPeriode->getList();
	$poste=$managerPoste->get($poste);

	$dateDebut=new DateTime($dateDebut);
	$dateFin = new DateTime($dateFin."+1day");
	$interval = DateInterval::createFromDateString('1 day');
	if ($periode=="scolaire") 
	{
		foreach(new DatePeriod($dateDebut, $interval, $dateFin) as $dt)
		{

			if(in_array($dt->format('N'), $jours))
			{
				$compt=0;
				foreach($periodes as $key)
				{
					$dateDebut=new DateTime($key['dateDebut']);
					$dateFin = new DateTime($key['dateFin']);
					if(($dt>=$dateDebut)&&($dt<=$dateFin))
					{
						$compt++;
					} 
				}
				if($compt==0) 
				{
					$infos=[
						'jour'=>$dt->format('Y-m-d'),
						'debut'=>$hdebut,
						'fin'=>$hfin
					];
					$heures_prevues=new heuresPrevues($infos);
					$managerHeuresPrevues->add($heures_prevues, $agent, $lieux, $type, $tache, $poste); 
				}
			}	
		}
	}else
	{
		foreach(new DatePeriod($dateDebut, $interval, $dateFin) as $dt)
		{

			if(in_array($dt->format('N'), $jours))
			{
				$compt=0;
				foreach($periodes as $key)
				{
					$dateDebut=new DateTime($key['dateDebut']);
					$dateFin = new DateTime($key['dateFin']);
					if(($dt>=$dateDebut)&&($dt<=$dateFin))
					{
						$infos=[
							'jour'=>$dt->format('Y-m-d'),
							'debut'=>$hdebut,
							'fin'=>$hfin
						];
						$heures_prevues=new heuresPrevues($infos);
						$managerHeuresPrevues->add($heures_prevues, $agent, $lieux, $type, $tache, $poste); 
					}	
				}
			}
		}
	}
	echo "Les heures de ".$agent->prenom()." ".$agent->nom()." ont bien été ajoutée.";
	
}

function editPlanning($mail)
{
	$db=connect_db();
	$managerAgent=new agentManager($db);
	$managerHeuresPrevues = new HeuresPrevuesManager($db);
	$agent=$managerAgent->get($mail);
	
	for ($i=0; $i <5 ; $i++) { 
		$_SESSION['horaires'][$i]=$managerHeuresPrevues->getPlanning($agent,$i+2);
	}	
	require('./view/editPlanningview.php');
}
function viewPlanning($mail){

	$db=connect_db();
	$heuresPrevuesManager=new heuresPrevuesManager($db);
	$heuresReellesManager = new heuresReellesManager($db);
	$managerAgent=new agentManager($db);
	$agent=$managerAgent->get($mail);

	$evenementNoValidate=$heuresPrevuesManager->getEvenementsNoValidate($agent);
	$evenementValidate=$heuresReellesManager->getEvenementsReelles($agent);
	//$evenementValidate = $heuresPrevuesManager->getEvenementsValidate($agent);
	$evenements= $heuresPrevuesManager->getEvenements($agent);
	

	$listEvenements =["noValidate"=>$evenementNoValidate, "validate"=>$evenementValidate, "evenements"=>$evenements];
	$i=0;
	$nomValidation="";
	$statusEvenements=[];
	foreach ($listEvenements as $validation) {
		if($validation == $listEvenements['noValidate'])
		{

			foreach ($listEvenements['noValidate'] as $key) 
			{
				$start = $key['jour'].' '.$key['debut'];
				$end = $key['jour'].' '.$key['fin'];
				$title = $key['tache'];

				$statusEvenements[$i] = ["title"=>$title,
							   		   "start"=>$start,
							   		   "end"=>$end,
							   		   "backgroundColor"=>"#c4250c",
							    	   "borderColor"=>"#c4250c"
										];
				$i++;
			}
		}elseif($validation==$listEvenements['validate'])
		{
			foreach ($listEvenements['validate'] as $key) {
				$start = $key['jour'].' '.$key['debut'];
				$end = $key['jour'].' '.$key['fin'];
				$title = $key['tache'];

				$statusEvenements[$i] = ["title"=>$title,
							   		   "start"=>$start,
							   		   "end"=>$end,
									   "backgroundColor"=>"#45b05c",
							   		   "borderColor"=>"#45b05c"
									   ];
				$i++;
			}
			
		}elseif($validation == $listEvenements['evenements'])
		{
			foreach ($listEvenements['evenements'] as $key) 
			{
				$start = $key['jour'].' '.$key['debut'];
				$end = $key['jour'].' '.$key['fin'];
				$title = $key['tache'];

				$statusEvenements[$i] = ["title"=>$title,
							  		   "start"=>$start,
							   		   "end"=>$end
									   ];
			$i++;
			}
		}	
	}
	$statusEvenements = json_encode($statusEvenements);

	require('./view/planningView.php');
	
}

function searchPlanning($mail, $jour, $debut)
{
	$db=connect_db();
	$managerAgent=new agentManager($db);
	$agent=$managerAgent->get($mail);
	$managerHeuresPrevues=new heuresPrevuesManager($db);

	$listPlanning = $managerHeuresPrevues->getListPlanning($agent, $jour, $debut);
	
	if($listPlanning!=null)
	{
		foreach ($listPlanning as $key) 
		{

			$jour=new DateTime($key['jour']);
			$jour=$jour->format('N');
		echo "
			
			<table class='table'>
				<tr>
					<th>Jour</th>
            		<th>Début</th>
            		<th>Fin</th>
            		<th>Tache</th>
            		<th>Lieu</th>
            		<th>Type</th>
            		<th>Poste</th>
				</tr>
				<tr>
            		<td>".$key['jour']."</td>
            		<td>".$key['debut']."</td>
            		<td>".$key['fin']."</td>
            		<td>".$key['tache']."</td>
            		<td>".$key['lieu']."</td>
            		<td>".$key['type']."</td>
            		<td>".$key['poste']."</td>
            		<td><form action='./index.php?action=edit' method='post'>

            		<input hidden type='text' name='jour' value='".$jour."'>
            		<input hidden type='time'  name='hdebut' value='".$key['debut']."'>
            		<input hidden type='time'  name='hfin' value='".$key['fin']."'>
            		<input hidden type='text'  name='tache' value='".$key['tache']."'>
            		<input hidden type='text'  name='lieu' value='".$key['lieu']."'>
            		<input hidden type='text'  name='type' value='".$key['type']."'>
            		<input hidden type='text'  name='poste' value='".$key['poste']."'>
            		<button type='submit'>Mettre à jour </button>
            		</form>
             	</tr>
             </table>
             ";

		}
	}else
	{
		echo "Fail";
	}
	
}

function formEditPlanning($jour, $hdebutTamp, $hfinTamp, $lieuTamp, $tacheTamp, $typeTamp, $posteTamp)  //_Tamp => donnée tampon
{
	$lists=getLists();
	// je met en mémoire les valeurs avant mofification afin de retrouver l'id de l'horaire et faire un update dans un deuxième temps.
	$_SESSION['hdebut']=$hdebutTamp;

	require('./view/editPlanningview.php');

}

function updatePlanning($mail, $periode, $dateDebut, $dateFin, $hdebut, $hfin, $type, $tache, $lieu, $poste, $jourRepeat)
{
	
	$db=connect_db();
	$managerLieux=new LieuxManager($db);
	$managerTache=new TacheManager($db);
	$managerType=new TypeManager($db);
	$managerPeriode=new PeriodeManager($db);
	$managerPoste = new PosteManager($db);
	$managerHeuresPrevues=new heuresPrevuesManager($db);
	$managerAgent=new agentManager($db);

	$agent=$managerAgent->get($mail);
	$lieu= $managerLieux->get($lieu);
	$tache= $managerTache->get($tache);
	$type= $managerType->get($type);
	$poste = $managerPoste->get($poste);
	$periodes=$managerPeriode->getList();

	$dateDebut=new DateTime($dateDebut);
	$dateFin = new DateTime($dateFin."+1day");
	$interval = DateInterval::createFromDateString('1 day');
	if ($periode=="scolaire") 
	{
		foreach(new DatePeriod($dateDebut, $interval, $dateFin) as $dt)
		{
			if(($dt->format('N')== $jourRepeat))
			{
				$jour=$dt->format('Y-m-d');
				$hdebutInit=$_SESSION['hdebut'];
				if($heures_prevuesInit=$managerHeuresPrevues->get($agent, $jour, $hdebutInit))
				{
					$compt=0;
					foreach($periodes as $key)
					{
						$dateDebut=new DateTime($key['dateDebut']);
						$dateFin = new DateTime($key['dateFin']);
						if(($dt>=$dateDebut)&&($dt<=$dateFin))
						{
							$compt++;
						} 
					}
					if($compt==0) 
					{
						$idHP=$heures_prevuesInit->id();
						$infosMaj=[
								'id'=>$idHP,
								'jour'=>$dt->format('Y-m-d'),
								'debut'=>$hdebut,
								'fin'=>$hfin
								];

						$heures_prevuesMaJ=new heuresPrevues($infosMaj);
						$managerHeuresPrevues->update($heures_prevuesMaJ, $agent, $lieu, $type, $tache, $poste);
					}
				}	
			}	
		}
	}else
	{
		foreach(new DatePeriod($dateDebut, $interval, $dateFin) as $dt)
		{

			if(in_array($dt->format('N'), $jours))
			{
				$compt=0;
				foreach($periodes as $key)
				{
					$dateDebut=new DateTime($key['dateDebut']);
					$dateFin = new DateTime($key['dateFin']);
					if(($dt>=$dateDebut)&&($dt<=$dateFin))
					{
						$infos=[
							'jour'=>$dt->format('Y-m-d'),
							'debut'=>$hdebut,
							'fin'=>$hfin
						];
						$heures_prevuesMaJ=new heuresPrevues($infos);
						$managerHeuresPrevues->update($heures_prevuesMaJ, $agent, $lieux, $type, $tache, $poste); 
					}	
				}
			}
		}
	}
	echo "Les heures ont été mises à jour.";
}
function deletePlanning($mail, $periode, $dateDebut, $dateFin, $hdebut, $hfin, $type, $tache, $lieu, $poste, $jourRepeat)
{
	$db=connect_db();
	$managerLieux=new LieuxManager($db);
	$managerTache=new TacheManager($db);
	$managerType=new TypeManager($db);
	$managerPeriode=new PeriodeManager($db);
	$managerPoste = new PosteManager($db);
	$managerHeuresPrevues=new heuresPrevuesManager($db);
	$managerAgent=new agentManager($db);

	$agent=$managerAgent->get($mail);
	$lieu= $managerLieux->get($lieu);
	$tache= $managerTache->get($tache);
	$type= $managerType->get($type);
	$poste = $managerPoste->get($poste);
	$periodes=$managerPeriode->getList();

	$dateDebut=new DateTime($dateDebut);
	$dateFin = new DateTime($dateFin."+1day");
	$interval = DateInterval::createFromDateString('1 day');

	foreach(new DatePeriod($dateDebut, $interval, $dateFin) as $dt)
	{
		if(($dt->format('N')== $jourRepeat))
		{
			$jour=$dt->format('Y-m-d');
			$hdebutInit=$_SESSION['hdebut'];
			if($heures_prevuesToDelete=$managerHeuresPrevues->get($agent, $jour, $hdebutInit))
			{
				$compt=0;
				foreach($periodes as $key)
				{
					$dateDebut=new DateTime($key['dateDebut']);
					$dateFin = new DateTime($key['dateFin']);
					if(($dt>=$dateDebut)&&($dt<=$dateFin))
					{
						$compt++;
					} 
				}
				if($compt==0) 
				{
					$managerHeuresPrevues->delete($heures_prevuesToDelete);
				}
			}	
		}	
	}
	echo "Les heures ont été supprimées.";
}
function validateThisDay($id, $type, $lieu, $tache, $poste, $nature )
{
	// factoriser ce code!
	$db=connect_db();

	$managerHeures= new heuresPrevuesManager($db);
	$managerAgent = new agentManager($db);
	$managerNature = new natureManager($db);
	$managerLieux=new LieuxManager($db);
	$managerTache=new TacheManager($db);
	$managerType=new TypeManager($db);
	$managerPoste = new PosteManager($db);
	$managerHeuresReelles = new heuresReellesManager($db);


	$heures=$managerHeures->getHeuresById($id);
	$data = ['jour'=>$heures->jour(), 'debut'=>$heures->debut(), 'fin'=>$heures->fin()];
	$heuresReelles= new heuresReelles($data);
	$agent = $managerAgent->get($_SESSION['infosUser']['mail']);
	$lieu=$managerLieux->get($lieu);
	$tache=$managerTache->get($tache);
	$type=$managerType->get($type);
	$poste = $managerPoste->get($poste);
	
	$nature = $managerNature->get($nature);

	if($managerHeures->valider($heures))
	{
		if($managerHeuresReelles->add($heuresReelles, $agent, $lieu, $type, $tache, $poste, $nature))
		{
			header("Location:index.php");
		}
	}
}

?>