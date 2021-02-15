<?php  
class HeuresPrevuesManager{

	private $_db;

	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}

	public function add(HeuresPrevues $heures, Agent $agent, Lieux $lieux, Type $type, Tache $tache, Poste $poste)
	{
		if(!self::exist($agent, $heures))
		{
			$q=$this->_db->prepare('INSERT INTO heuresPrevues(jour, debut, fin, validee, id_tache, id_agent, id_lieu, id_type, id_poste) VALUES (:jour, :debut, :fin, :validee, :id_tache, :id_agent, :id_lieu, :id_type, :id_poste) ;');

			$q->bindValue('jour',$heures->jour());
			$q->bindValue('debut',$heures->debut());
			$q->bindValue('fin',$heures->fin());
			$q->bindValue('validee', $heures->getValidee());
			$q->bindValue('id_tache',$tache->id(), PDO::PARAM_INT);
			$q->bindValue('id_agent',$agent->id(), PDO::PARAM_INT);
			$q->bindValue('id_lieu',$lieux->id(), PDO::PARAM_INT);
			$q->bindValue('id_type',$type->id(), PDO::PARAM_INT);
			$q->bindValue('id_poste',$poste->id(), PDO::PARAM_INT);
	
			$q->execute();
			return true;
		}else
		{
			return $message=['jour'=>$heures->jour(),'debut'=> $heures->debut(), 'fin'=> $heures->fin() ];
		}
	}

	public function update(HeuresPrevues $heures, Agent $agent, Lieux $lieux, Type $type, Tache $tache, Poste $poste)
	{
		$q=$this->_db->prepare('UPDATE heuresPrevues SET jour=:jour, debut=:debut, fin=:fin, id_tache=:id_tache, id_agent=:id_agent, id_lieu=:id_lieu, id_type=:id_type, id_poste= :id_poste WHERE id=:id;');

		$q->bindValue('jour',$heures->jour());
		$q->bindValue('debut',$heures->debut());
		$q->bindValue('fin',$heures->fin());
		$q->bindValue('id_tache',$tache->id(), PDO::PARAM_INT);
		$q->bindValue('id_agent',$agent->id(), PDO::PARAM_INT);
		$q->bindValue('id_lieu',$lieux->id(), PDO::PARAM_INT);
		$q->bindValue('id_type',$type->id(), PDO::PARAM_INT);
		$q->bindValue('id_poste',$poste->id(), PDO::PARAM_INT);
		$q->bindValue('id', $heures->id(), PDO::PARAM_INT);
		if($q->execute())
		{
			return true;
		}else
		{
			return false;
		}
	}

	public function exist(Agent $agent, HeuresPrevues $heures)
	{
		$requete='SELECT COUNT(*) FROM heuresPrevues WHERE id_agent=:id_agent AND jour=:jour AND debut=:debut;';
		$q=$this->_db->prepare($requete);
		$q->execute([
					':id_agent'=>$agent->id(), 
					':jour'=>$heures->jour(), 
					':debut'=>$heures->debut()]);

		return (bool) $q->fetchColumn();
	}

	public function deleteAllFromAgent($agent)
	{
		if($this->_db->exec('DELETE FROM heuresPrevues WHERE id_agent='.$agent->id()))
		{
			return true;
		}
	}

	public function delete(HeuresPrevues $heures)
	{
		$this->_db->exec('DELETE FROM heuresPrevues WHERE id='.$heures->id());
	}
	public function get(Agent $agent, $jour, $hdebut)
	{
		$q=$this->_db->prepare('SELECT id, jour, debut, fin FROM heuresPrevues WHERE jour=:jour AND debut=:debut AND id_agent=:id_agent;');
		$q->execute([
			'jour'=>$jour,
			'debut'=>$hdebut,
			'id_agent'=>$agent->id()
			]);

		$data = $q->fetch(PDO::FETCH_ASSOC);
		if(is_array($data))
		{
			return new heuresPrevues($data);
		}else
		{
			return false;
		}
	}
	
	public function getListPlanning(Agent $agent, $jour, $debut)
	{
		$q=$this->_db->prepare('SELECT hp.id, jour, debut, fin, tache, lieu, type, poste FROM heuresPrevues hp, tache t , lieux l , type tp, postes p WHERE hp.id_tache=t.id AND hp.id_lieu=l.id AND hp.id_type=tp.id AND hp.id_poste = p.id AND id_agent=:id_agent AND jour=:jour AND debut=:debut;');
		$q->execute([
			'id_agent'=>$agent->id(),
			'jour'=>$jour,
			'debut'=>$debut
		]);
		$data=$q->fetchAll(PDO::FETCH_ASSOC);
		if(is_array($data))
		{
		return $data;
		}
	}

	public function getHeuresById($id){
		$id=intval($id);
		{
			$q=$this->_db->prepare('SELECT id, jour, debut, fin FROM heuresPrevues WHERE id=:id;');
			$q->execute([
				'id'=>$id
				]);

			$data = $q->fetch(PDO::FETCH_ASSOC);
			if(is_array($data))
			{
				return new heuresPrevues($data);
			}
		}	
	}

	public function getPlanning(Agent $agent, $jour)
	{
		$q=$this->_db->prepare("SELECT DISTINCT debut, fin, tache, lieu, type FROM heuresPrevues as hp INNER JOIN  tache as t ON hp.id_tache=t.id INNER JOIN  lieux as l ON hp.id_lieu=l.id INNER JOIN type as tp ON hp.id_type=tp.id WHERE id_agent=:id_agent AND DAYOFWEEK(jour)=:jour ORDER BY debut ASC;");
		$q->execute([
			'id_agent'=>$agent->id(),
			'jour'=>$jour
		]);
		
		
		return $donnees=$q->fetchAll(PDO::FETCH_ASSOC);
	}

	public function afficherHeures(Agent $agent)
	{
		$liste=[];
		$compt=0;
		$q=$this->_db->query("SELECT hp.id, jour, debut, fin, tache, lieu, type, poste FROM heuresPrevues as hp INNER JOIN  tache as t ON hp.id_tache=t.id INNER JOIN  lieux as l ON hp.id_lieu=l.id INNER JOIN type as tp ON hp.id_type=tp.id INNER JOIN postes as pst ON hp.id_poste=pst.id WHERE jour<DATE(NOW()) AND validee=0 AND id_agent=".$agent->id()." ORDER BY jour ASC;");
		while($data = $q->fetch(PDO::FETCH_ASSOC))
		{
			$liste[$compt]=$data;
			$compt++;
		}
		return $liste;
	}

	public function valider(heuresPrevues $heures) 
	{
		$heures->valider();
		$q=$this->_db->prepare("UPDATE heuresPrevues SET validee =:validee WHERE id=:id;");
		
		$q->bindValue('validee',$heures->getValidee());
		$q->bindValue('id',$heures->id());
		if($q->execute())
		{
			return true;
		}
	}

	public function totalHeuresAgent(Agent $agent, $id_type='')
	{
		$donnees='';
		if((isset($id_type))&&((is_int($id_type))))
		{
			if(($id_type==1)||($id_type==2))
			{
				$q=$this->_db->prepare('SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(fin, debut)))) AS HeuresPrevueTotales FROM heuresPrevues WHERE id_agent=:id_agent AND id_type=:id_type;');
				$q->execute(['id_agent'=>$agent->id(),
					 		 'id_type'=>$id_type
							]);
				$donnees=$q->fetch(PDO::FETCH_ASSOC);
			}
		}else
		{
			$q=$this->_db->prepare('SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(fin, debut)))) AS HeuresPrevueTotales FROM heuresPrevues WHERE id_agent=:id_agent');
				$q->execute(['id_agent'=>$agent->id()
							]);
				$donnees=$q->fetch(PDO::FETCH_ASSOC);
		}
		return $donnees;	
	}

	public function totalHeuresByTache(Agent $agent, $type, $tache)
	{
		$donnees='';
		if (is_string($tache)) 
		{
			$q=$this->_db->prepare('SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(fin, debut)))) AS HeuresPrevueTotales FROM heuresPrevues hp, tache t WHERE t.id=hp.id_tache AND id_agent=:id_agent AND id_type=:id_type AND t.service=:tache;');
			$q->execute(['id_agent'=>$agent->id(),
						 'id_type'=>$type,
					 	 'tache'=>$tache
							]);
			$donnees=$q->fetch(PDO::FETCH_ASSOC);
		}
		return $donnees;
	}

	public function totalHeuresDates(Agent $agent, $debut, $fin, Type $type=null)
	{
		if($type=='')
		{
			$q=$this->_db->prepare('SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(fin, debut)))) AS HeuresPrevueTotales
									FROM heuresPrevues WHERE id_agent=:id_agent BETWEEN :debut AND :fin ;');
			$q->execute([
				'id_agent'=>$agent->id(),
				'debut'=>$debut,
				'fin'=>$fin]);
			return $q->fetch(PDO::FETCH_ASSOC);
		}else
		{
			$q=$this->_db->prepare('SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(fin, debut)))) AS HeuresPrevueTotales
									FROM heuresPrevues WHERE id_agent=:id_agent AND id_type=:type BETWEEN `:debut``
									 AND `:fin` ;');
			$q->execute([
				'id_agent'=>$agent->id(),
				'id_type'=>$type->id(),
				'debut'=>$debut,
				'fin'=>$fin]);
			return new heuresPrevues ($q->fetch(PDO::FETCH_ASSOC));
		}
	}
	public function getEvenementsNoValidate(Agent $agent)
	{
		$q=$this->_db->prepare('SELECT  jour, debut, fin, tache FROM heuresPrevues hp, tache t WHERE hp.id_tache = t.id AND jour<DATE(NOW()) AND validee=0 AND id_agent=:id_agent ');
		$q->execute([
			'id_agent'=>$agent->id(),
			]);
		$data=$q->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}

	public function getEvenements(Agent $agent)
	{
		$q=$this->_db->prepare('SELECT  jour, debut, fin, tache FROM heuresPrevues hp, tache t WHERE hp.id_tache = t.id AND jour>=DATE(NOW()) AND id_agent=:id_agent ');
		$q->execute([
			'id_agent'=>$agent->id(),
			]);
		$data=$q->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}

	public function setDb(PDO $db)
	{
		$this->$_db;
	}
}
?>