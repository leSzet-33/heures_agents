<?php
// CONTROLLER
require ("./model/agent/agent.php");
require ("./model/agent/administrateur.php");
require ("./model/agent/agentManager.php");
require ("./install/connect.php");

if(!isset($_SESSION))
{
    session_start();
}

function valid_donnee(string $donnee):string
{
	$donnee=trim($donnee);
	$donnee=stripslashes($donnee);
	$donnee=htmlspecialchars($donnee);

	return $donnee;
}

function searchAgent($nomAgent='')
{
    $nomAgent=valid_donnee($nomAgent);
    $db=connect_db();
    if($nomAgent!='')
    {
        $agents= new AgentManager($db);
        $listAgents=$agents->searchAgent($nomAgent);
        if(!empty($listAgents)){
            foreach ($listAgents as $key) {
            echo "<tr>
                    <th>".$key['nom']."</th><th>".$key['prenom']."</th><th>".ucfirst($key['poste'])."</th>
                  </tr>
                  <tr>
                  <th colspan=3>
                    <form action='./index.php?action=manageAgent' method='post'>
                        <button type='submitt' name='manageAgent' value='".$key['mail']."' class='btn btn-secondary'>gérer</button>
                        <button type='submitt' name='editAgent' value='".$key['mail']."' class='btn btn-secondary'>modifier</button>
                    </form>
                  </th>
                  </tr>";
            }
        }else
        {
            echo "<tr><th> Aucun agent trouvé dans la base de donnée<th><tr>";
        }
    }else
    {
        $message="Veuillez selectionner/créer un agent pour créer/modifier son emploi du temps.";
        require('./view/homeViewAdmin.php');    
    }
    
}

function getInfos($mail)
{
    $db=connect_db();
    $agentManager= new AgentManager($db);
    if($mail!="")
    {
        if($agent=$agentManager->get($mail))
        {
            return $infos=['id'=>$agent->id(),'nom'=>$agent->nom(), 'prenom'=>$agent->prenom(), 'mail'=>$agent->mail(), 'pass'=>$agent->pass(), 'poste'=>$agent->poste(), 'role'=>$agent->role()];
        }else
        {
            return false;
        }  
    }else
    {
        return false;
    }
    
}

function homePage()
{
    if($_SESSION['infosUser']['role']==2)
    {
        $db=connect_db();
        $managerPoste = new PosteManager($db);
        $listePostes=$managerPoste->getList();
        $hidden=null;
        require('view/homeViewAdmin.php');
    }else
    {
        
        $db=connect_db();
        $agentManager=new AgentManager($db);
        $agent=$agentManager->get($_SESSION['infosUser']['mail']);
        $heuresManager= new heuresPrevuesManager($db);
        $heuresToCheck=$heuresManager->afficherHeures($agent);
        $hidden="hidden";
        $heuresReelles = new heuresReellesManager($db);
        $totalReelles=$heuresReelles->totalHeuresEffectueesAgent($agent);
        $totalPrevue=$heuresManager->totalHeuresAgent($agent);


        if(intval($totalPrevue['HeuresPrevueTotales'])!=0)
        {
            $progress =  ((intval($totalReelles['HeuresReellesTotales']))/(intval($totalPrevue['HeuresPrevueTotales'])))*100;
            $progress = round($progress);

            $rest = (intval($totalPrevue['HeuresPrevueTotales'])-intval($totalReelles['HeuresReellesTotales']))/intval($totalPrevue['HeuresPrevueTotales'])*100;
            $rest = round($rest);
        }else
        {
            $progress=null;
        }   
        require('view/homeViewAgent.php');
    }
}

function toLogin()
{
    header("Location:./view/connexionView.php");
}


function connexionVerify($mailUser, $passUser)
{
    $email = valid_donnee($mailUser);
    $pass_user=valid_donnee($passUser);
    $user=getInfos($email);
    $agentPass=$user['pass'];
    $isCorrectPassword=password_verify($pass_user, $agentPass);
    if(!is_array($user))
    {
        header("Location: ./view/connexionView.php");
    }else
    {
        if($isCorrectPassword)
        {
            $_SESSION['infosUser']=['nom'=>$user['nom'],'prenom'=>$user['prenom'],'mail'=>$user['mail'],'role'=>$user['role']];
            $_SESSION['connected']=true;
            header('Location:./index.php');
        }else
        {
            header("Location: ./index.php");
        }
    }

}

function manageAgent($mail)
{
    $db=connect_db();
    $agent=getInfos($mail);
    $_SESSION['infosAgent']=['nom'=>$agent['nom'], 'prenom'=>$agent['prenom'],'mail'=>$agent['mail'], 'poste'=>$agent['poste'], 'role'=>$agent['role']];
    header('Location:./index.php?page=planningCreate');
}

function editAgent($mail){
    $db =connect_db();
    $agent = getInfos($mail);
    $mail=$agent['mail'];
    require ('view/editAgentView.php');

}

function updateAgent($mailTampon, $nom, $prenom, $mail, $poste)
{
    $db=connect_db();
    $nom=valid_donnee($nom);
    $prenom = valid_donnee($prenom);
    $mail= valid_donnee($mail);
    $infos = getInfos($mailTampon);
    $agentManager= new AgentManager($db);
    $agent=$agentManager->get($infos['mail']);
    $infosUpdate = ['id'=>$agent->id(), 'nom'=>$nom, 'prenom'=>$prenom, 'mail'=>$mail, "poste"=>$poste];
    $agentUpDate = new Agent($infosUpdate);
    if($agentManager->update($agentUpDate))
    {
       header("Location: index.php?action=manageAgent&mail=".$infosUpdate['mail']);
    }
}

function addAgent(Array $infos)
{
    // je vérifie les données via la fontion valid_donnee() dans une boucle.
    foreach ($infos as $key=>$value) {
      $value = valid_donnee($value);

    }
    if($infos['pass']==$infos['passRepeat'])
    {
        $db=connect_db();
        $agentManager= new AgentManager($db);
        $agent= new Agent($infos);
        if((!$agentManager->exist($agent->mail()))){
            if($agentManager->add($agent))
            {   
            $infosAgent=getInfos($agent->mail());
            $_SESSION['infosAgent']=$infosAgent;
            echo "L'agent ".$infosAgent['prenom']." ".$infosAgent['nom']." a bien été créé. Il est maintenant en gestion.";
            }else
            {
                echo "Erreur lors de la création de l'agent.";
            }
        }else
        {
            echo "Il y a déjà cette adresse mail dans la base de donnée";
        }
        
    }else
    {
        echo "Pas de correspondance des mots de passes. Veuillez réessayer. ";
    }
   
}

function infosAgent($mail)
{
    $db=connect_db();
    $heures= new heuresPrevuesManager($db);

    $agentManager= new AgentManager($db);
    $agent=$agentManager->get($mail);


    $infos=getInfos($mail);



    $totalAnn=$heures->totalHeuresAgent($agent, 1); // 1 correspond à la clé étrangère du type 'annualisées'. -> mettre dans une constante!!!
    $totalComp=$heures->totalHeuresAgent($agent, 2);// 2 coorespond à la clé étrangère du type 'complémentaire'.
    $total=$heures->totalHeuresAgent($agent);
    // total des heures en fonction des taches.

    $totalClaeAnn=$heures->totalHeuresByTache($agent, 1, 'CLAE'); 
    $totalClaeComp=$heures->totalHeuresByTache($agent, 2, 'CLAE');

    $totalMercAnn=$heures->totalHeuresByTache($agent, 1, 'Plan Mercredis');
    $totalMercComp=$heures->totalHeuresByTache($agent, 2, 'Plan Mercredis');

    $totalALSHAnn=$heures->totalHeuresByTache($agent, 1, 'ALSH');
    $totalALSHComp=$heures->totalHeuresByTache($agent, 2, 'ALSH');

    $totalAutresAnn=$heures->totalHeuresByTache($agent, 1, 'Autres');
    $totalAutresComp=$heures->totalHeuresByTache($agent, 2, 'Autres');
    require('view/infosAgentView.php');
} 

function getEtatHeures($mail, $month="")
{
    $db=connect_db();
    $heuresPrevues= new heuresPrevuesManager($db);
    $heuresReelles = new heuresReellesManager($db);
    $agentManager= new AgentManager($db);
    $agent=$agentManager->get($mail);
    $infos = getInfos($mail);
    $totalReelles=$heuresReelles->totalHeuresEffectueesAgent($agent);
    $totalPrevue=$heuresPrevues->totalHeuresAgent($agent);
    if(intval($totalPrevue['HeuresPrevueTotales'])!=0){
         $progress =  ((intval($totalReelles['HeuresReellesTotales']))/(intval($totalPrevue['HeuresPrevueTotales'])))*100;
        $progress = round($progress);
    }else
    {
        $progress=null;
    }
    // heures reelles par mois par type.
    $heuresAnnByMonth = $heuresReelles->totalHeuresEffectueesAgentByMonth($agent ,$month, 1 );
    $heuresCompByMonth = $heuresReelles->totalHeuresEffectueesAgentByMonth($agent ,$month, 2 );
    $heuresTotalesByMonth = $heuresReelles->totalHeuresEffectueesAgentByMonth($agent,$month);
    if(empty($month)){
        
        require('view/etatHeuresView.php');

   }else
   {
        $data=["annualisées"=>$heuresAnnByMonth['reellesThisMonthByType'],
               "complémentaires" =>$heuresCompByMonth['reellesThisMonthByType'],
               "totales"=>$heuresTotalesByMonth['reellesThisMonth']
         ];
         echo json_encode($data);
   }  
}

function switchAgent()
{
    unset($_SESSION['infosAgent']);
    echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
}
function deconnexion()
{
    session_destroy();
    echo "<script type='text/javascript'>document.location.replace('index.php');</script>";

    //header("Location:index.php");
}
function deleteAgent($mail)
{
    $db= connect_db();
    $agentManager=new AgentManager($db);
    $agent= $agentManager->get($mail);

    $heuresPrevuesManager = new heuresPrevuesManager($db);
    $heuresReellesManager = new heuresReellesManager($db);

    $heuresPrevuesManager->deleteAllFromAgent($agent);
    $heuresReellesManager->deleteAllFromAgent($agent);
    unset($_SESSION['infosAgent']);
    if($agentManager->delete($agent))
        {

            echo "Agent supprimé";

        }else
        {
            echo "Erreur sur la suppression de l'agent";
        }
}
?>