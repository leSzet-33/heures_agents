<?php  
class HeuresReellesManager{

	private $_db;

	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}

	public function add(heuresReelles $heures, Agent $agent, Lieux $lieux, Type $type, Tache $tache, Poste $poste, Nature $nature)
	{
		
		if(!self::exist($agent, $heures))
		{
			$q=$this->_db->prepare('INSERT INTO heuresReelles(jour, debut, fin, id_tache, id_agent, id_lieu, id_type, id_poste, id_nature) VALUES (:jour, :debut, :fin, :id_tache, :id_agent, :id_lieu, :id_type, :id_poste, :id_nature) ;');

			$q->bindValue('jour',$heures->jour());
			$q->bindValue('debut',$heures->debut());
			$q->bindValue('fin',$heures->fin());
			$q->bindValue('id_tache',$tache->id(), PDO::PARAM_INT);
			$q->bindValue('id_agent',$agent->id(), PDO::PARAM_INT);
			$q->bindValue('id_lieu',$lieux->id(), PDO::PARAM_INT);
			$q->bindValue('id_type',$type->id(), PDO::PARAM_INT);
			$q->bindValue('id_poste',$poste->id(), PDO::PARAM_INT);
			$q->bindValue('id_nature',$nature->id(), PDO::PARAM_INT);

			$q->execute();
			return true;
		}
		else
		{
			echo $agent->prenom().' '.$agent->nom()." travaille déjà le ". $heures->jour(). " de ".$heures->debut()." à ".$heures->fin();
		}


	}

	public function update(HeuresReelles $heures, Lieux $lieux, Type $type, Tache $tache, Poste $poste)
	{
		
		$q=$this->_db->prepare('UPDATE heuresReelles SET jour=:jour, debut=:debut, fin=:fin, id_tache=:id_tache, id_lieu=:id_lieu, id_type=:id_type, id_poste=:id_poste  WHERE id=:id;');

			$q->bindValue('jour',$heures->jour());
			$q->bindValue('debut',$heures->debut());
			$q->bindValue('fin',$heures->fin());
			$q->bindValue('id_tache',$tache->id(), PDO::PARAM_INT);
			$q->bindValue('id_lieu',$lieux->id(), PDO::PARAM_INT);
			$q->bindValue('id_type',$type->id(), PDO::PARAM_INT);
			$q->bindValue('id', $heures->id(), PDO::PARAM_INT);

			if($q->execute())
			{
				return true;
			}else
			{
				return false;
			}
	}

	public function exist(Agent $agent, HeuresReelles $heures)
	{
		$requete='SELECT COUNT(*) FROM heuresReelles WHERE id_agent=:id_agent AND jour=:jour AND debut=:debut;';
		$q=$this->_db->prepare($requete);
		$q->execute([
					':id_agent'=>$agent->id(), 
					':jour'=>$heures->jour(), 
					':debut'=>$heures->debut()]);

		return (bool) $q->fetchColumn();

	}

	public function delete(HeuresReelles $heures)
	{
		$this->_db->exec('DELETE FROM heuresReelles WHERE id='.$heures->id());
	}

	public function deleteAllFromAgent($agent)
	{
		if($this->_db->exec('DELETE FROM heuresReelles WHERE id_agent='.$agent->id()))
		{
			return true;
		}
	}

	public function get(Agent $agent, HeuresPrevues $heures)
	{
		$q=$this->_db->prepare('SELECT id, jour, debut, fin FROM heuresPrevues WHERE id_agent=:id_agent AND jour=:jour AND debut=:debut;');
		$q->execute([
			'id_agent'=>$agent->id(),
			'jour'=>$heures->jour(),
			'debut'=>$heures->debut()
		]);
		$data=$q->fetch(PDO::FETCH_ASSOC);
		if(is_array($data))
		{
		return new heuresReelles($data);
		}
	}

	public function getHeuresById($id){
		$id=intval($id);
		{
			$q=$this->_db->prepare('SELECT id, jour, debut, fin FROM heuresReelles WHERE id=:id;');
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

	public function getList(Agent $agent, $debut , $fin, $jours)
	{
		$jsemaine=['Samedis','Dimanche','Lundis','Mardis','Mercredis','Jeudis','Vendredis'];
		$q=$this->_db->prepare('SELECT id, jour, debut , fin FROM heuresPrevues WHERE id_agent=:id_agent AND debut=:debut AND fin=:fin AND DAYOFWEEK(jour)=:jours;');
		$q->execute([
			'id_agent'=>$agent->id(),
			'debut'=>$debut,
			'fin'=>$fin,
			'jours'=>$jours
		]);

		$data =$q->fetch(PDO::FETCH_ASSOC);
		if(is_array($data))
		{
			return new heuresPrevues($data);
		}else
		{
			$message='Aucune heure prévue aux horaires indiqués les '.$jsemaine[$jours].'.';
		}
	}
	

	public function totalHeuresEffectueesAgent(Agent $agent)
	{
		$q=$this->_db->prepare('SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(fin, debut)))) AS HeuresReellesTotales
								FROM heuresReelles WHERE id_agent=:id_agent;');
		$q->execute(['id_agent'=>$agent->id()]);
		return $q->fetch(PDO::FETCH_ASSOC);
		
	}

	public function totalHeuresEffectueesAgentByMonth(Agent $agent, $month="", $type="")
	{
		if(!empty($month)){
			if((intval($month)>0)&&(intval($month)<=12)){
				if($type==""){
					$q=$this->_db->prepare('SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(fin, debut)))) AS reellesThisMonth
								FROM heuresReelles WHERE id_agent=:id_agent AND MONTH(jour)=:month;');
					$q->execute(['id_agent'=>$agent->id(),
					 		 'month' =>$month
								]);
					return $q->fetch(PDO::FETCH_ASSOC);
				}else{
					$q=$this->_db->prepare('SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(fin, debut)))) AS reellesThisMonthByType
								FROM heuresReelles WHERE id_agent=:id_agent AND MONTH(jour)=:month AND id_type=:type ;');
					$q->execute(['id_agent'=>$agent->id(),
					 		 'month' =>$month,
					 		 'type' =>$type
					 			]);
					return $q->fetch(PDO::FETCH_ASSOC);
				}
			}	
		}else
		{
			if(empty($type))
			{
				$q=$this->_db->prepare('SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(fin, debut)))) AS reellesThisMonth
								FROM heuresReelles WHERE id_agent=:id_agent AND MONTH(jour)=MONTH(now())');
				$q->execute(['id_agent'=>$agent->id()
							]);
				return $q->fetch(PDO::FETCH_ASSOC);
			}else{
				$q=$this->_db->prepare('SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(fin, debut)))) AS reellesThisMonthByType
								FROM heuresReelles WHERE id_agent=:id_agent AND MONTH(jour)=MONTH(now()) AND id_type=:type ;');
				$q->execute(['id_agent'=>$agent->id(),
					 		 'type' =>$type
					 		]);
				return $q->fetch(PDO::FETCH_ASSOC);
			}
		}	
	}

	public function getEvenementsReelles(Agent $agent)
	{
		$q=$this->_db->prepare('SELECT  jour, debut, fin, tache FROM heuresReelles hp, tache t WHERE hp.id_tache = t.id AND id_agent=:id_agent ');
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