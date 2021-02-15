<?php
//ROUTER
require ("controller/userController.php");//controller de connexion.
require("controller/planningController.php");
if(!isset($_SESSION))
{
    session_start();
}
// si la session connected n'existe pas:
if(!isset($_SESSION['connected']))
{
	if(isset($_GET["action"]))
	{
		if($_GET['action']=='connexion')
		{
			if(isset($_POST['mailUser'])&&isset($_POST['password']))
			{
				$mailUser=$_POST['mailUser'];
				$passUser=$_POST['password'];
				connexionVerify($mailUser, $passUser);
			}
		}
	}else
	{
		toLogin();
	}

}else
{
	if (isset($_GET['action']))
	{
		if($_SESSION['infosUser']['role']==2)
		{
			if($_GET['action']=='addAgent')
			{
			$infos=['nom'=>$_POST['nom'],
				'prenom'=>$_POST['prenom'],
				'mail'=>$_POST['mailAgent'],
				'pass'=>$_POST['pass'],
				'passRepeat'=>$_POST['passRepeat'],
				'poste'=>$_POST['poste']
				];
			addAgent($infos);

			}elseif($_GET['action']=='searchAgent')
			{
				$agentSearch=$_POST['searchAgent'];
				searchAgent($agentSearch);

			}elseif($_GET['action']=='manageAgent')
			{
				if(isset($_POST['manageAgent']))
				{
					manageAgent($_POST['manageAgent']);

				}elseif(isset($_POST['editAgent']))
				{
					editAgent($_POST['editAgent']);

				}elseif(isset($_POST['updateAgent']))
				{
					updateAgent($_POST['mailAgentTampon'], $_POST['nom'], $_POST['prenom'], $_POST['mailAgent'], $_POST['poste']);

				}elseif(isset($_POST['deleteAgent']))
				{
					deleteAgent($_POST['deleteAgent']);

				}else
				{
					manageAgent($_GET['mail']);

				}
			}elseif(($_GET['action']=='addPlanning')&&isset($_SESSION['infosAgent']))
			{
				addPlanning($_SESSION['infosAgent']['mail'], $_POST['periode'], $_POST['debut'],$_POST['fin'], $_POST['hdebut'],$_POST['hfin'], $_POST['type'],$_POST['tache'], $_POST['lieu'], $_POST['poste'], $_POST['repeter_jour']);

			}elseif(($_GET['action']=='searchPlanning')&&isset($_SESSION['infosAgent']))
			{
				searchPlanning($_SESSION['infosAgent']['mail'], $_POST['jour'],$_POST['debut']);

			}elseif(($_GET['action']==='edit')&&isset($_SESSION['infosAgent']))
			{
				formEditPlanning($_POST['jour'], $_POST['hdebut'],$_POST['hfin'],$_POST['lieu'],$_POST['tache'],$_POST['type'], $_POST['poste']);

			}elseif (($_GET['action']==='updatePlanning')&&(!isset($_POST['deletePlanning']))&&isset($_SESSION['infosAgent'])) 
			{
				updatePlanning($_SESSION['infosAgent']['mail'], $_POST['periode'], $_POST['debut'], $_POST['fin'], $_POST['hdebut'], $_POST['hfin'], $_POST['type'], $_POST['tache'], $_POST['lieu'], $_POST['poste'], $_POST['jour']);

			}elseif(($_GET['action']==='updatePlanning')&&isset($_SESSION['infosAgent']))
			{
				deletePlanning($_SESSION['infosAgent']['mail'], $_POST['periode'], $_POST['debut'], $_POST['fin'], $_POST['hdebut'], $_POST['hfin'], $_POST['type'], $_POST['tache'], $_POST['lieu'], $_POST['poste'], $_POST['jour']);

			}elseif (($_GET['action']==='switchAgent')&&isset($_SESSION['infosAgent'])) 
			{
				switchAgent();
			}
		}elseif($_SESSION['infosUser']['role']==1)
		{
			if(($_GET['action']=='checkDay')&& isset($_POST['validateThisDay']))
			{
				validateThisDay($_POST['id_heures'], $_POST['type'], $_POST['lieu'], $_POST['tache'], $_POST['poste'], $_POST['validateThisDay']);

			}
		}
	}elseif(isset($_GET['page']))
	{
		if(($_GET['page']=='planningCreate')&&($_SESSION['infosUser']['role']==2))
		{
			if(isset($_SESSION['infosAgent']))
			{
				planningCreatePage();
			}else{
				searchAgent();
			}
		}elseif(($_GET['page']=='planningEdit')&&($_SESSION['infosUser']['role']==2))
		{
			if(isset($_SESSION['infosAgent']))
			{
				editPlanning($_SESSION['infosAgent']['mail']);
			}else
			{
				searchAgent();
			}
		}elseif($_GET['page']=='planningView')
		{
			if($_SESSION['infosUser']['role']==2)
			{
				if(isset($_SESSION['infosAgent']))
				{
					viewPlanning($_SESSION['infosAgent']['mail']);
				}else{
					searchAgent();
				}
			}elseif($_SESSION['infosUser']['role']==1)
			{
				viewPlanning($_SESSION['infosUser']['mail']);
			}
		
		}elseif($_GET['page']=='infosAgent')
		{
			if(isset($_SESSION['infosAgent']))
			{
				infosAgent($_SESSION['infosAgent']['mail']);
			}elseif((!isset($_SESSION['infosAgents'])&&($_SESSION['infosUser']['role']==2)))
			{
				searchAgent();
			}else
			{
				infosAgent($_SESSION['infosUser']['mail']);
			}
		}elseif($_GET['page']=="etatHeures")
		{
			if($_SESSION['infosUser']['role']==2)
			{
				if(isset($_SESSION['infosAgent']))
				{
					if(isset($_POST['month']))
					{
						getEtatHeures($_SESSION['infosAgent']['mail'], $_POST['month']);	
					}else
					{
						getEtatHeures($_SESSION['infosAgent']['mail']);
					}
			
				}else
				{
					searchAgent();
				}
			}elseif($_SESSION['infosUser']['role']==1)
			{
				if(isset($_POST['month']))
				{
					getEtatHeures($_SESSION['infosUser']['mail'], $_POST['month']);	
				}else
				{
					getEtatHeures($_SESSION['infosUser']['mail']);
				}
			}
		}
	}else
	{
		homePage();

	}
}

if(isset($_GET['deconnexion']))
{
	deconnexion();
}
?>