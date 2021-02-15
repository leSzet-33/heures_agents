<?php 
class AgentManager{

	private $_db;

	private function valid_donnee(string $donnee):string
	{
	$donnee=trim($donnee);
	$donnee=stripslashes($donnee);
	$donnee=htmlspecialchars($donnee);

	return $donnee;
	}

	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}

	public function add(Agent $agent)
	{
		$passHash= password_hash($agent->pass(),  PASSWORD_DEFAULT);
			$q=$this->_db->prepare('INSERT INTO agents (nom, prenom, pass, mail, poste, id_role) VALUES (:nom, :prenom, :pass, :mail, :poste, :role)');

			$q->bindValue(':nom', $agent->nom());
			$q->bindValue(':prenom', $agent->prenom());
			$q->bindValue(':pass', $passHash);
			$q->bindValue(':mail', $agent->mail());
			$q->bindValue(':poste', $agent->poste());
			$q->bindValue(':role', $agent->role());

			if($q->execute())
			{
				return true;
			}
			else
			{
				return false;
			}
			
	}

	public function delete(Agent $agent)
	{
		$this->_db->exec('DELETE FROM agents WHERE id='.$agent->id().";");
		return true;
	}

	public function update(Agent $agent)
	{
		$q=$this->_db->prepare('UPDATE agents SET nom=:nom, prenom=:prenom,/*pass=:pass*/ mail=:mail, poste=:poste WHERE id='.$agent->id());

		$q->bindValue(':nom', $agent->nom());
		$q->bindValue(':prenom', $agent->prenom());
		//$q->bindValue(':pass', $agent->pass());
		$q->bindValue(':mail', $agent->mail());
		$q->bindValue(':poste', $agent->poste());

		$q->execute();
		return true;
	}

	public function searchAgent($nomAgent)
	{
		if(isset($nomAgent))
		{
			$agent=valid_donnee($nomAgent);
			$agent="%".strtolower($agent)."%";
			$q=$this->_db->prepare('SELECT id, nom, prenom, mail, poste FROM agents WHERE nom LIKE LOWER(:nom)');
			$q->execute([':nom'=>$agent]);
			$donnee=$q->fetchAll(PDO::FETCH_ASSOC);
			if(is_array($donnee))
			{
				return $donnee;
			}else
			{
				return $erreur= 'Aucun agent trouvé. ';
			}
		}
	}

	public function exist($mail)
	{
		$pattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,18}$/";
		if(is_string($mail)&&(strlen($mail)<50))
		{
			if(preg_match($pattern, $mail))
			{
				$q=$this->_db->prepare('SELECT COUNT(*) FROM agents WHERE mail=:mail');
				$q->execute([':mail'=>$mail]);
				return (bool) $q->fetchColumn();
			}
		}
	}

	public function get($mail)
	{
		if($mail!='')
		{
			$mail=valid_donnee($mail);
			$pattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,18}$/";
			if(is_string($mail)&&(strlen($mail)<50))
			{
				if(preg_match($pattern, $mail))
				{
					$q=$this->_db->prepare('SELECT id,nom,prenom, pass, mail, poste, id_role FROM agents WHERE mail=:mail');
					$q->execute([':mail'=>$mail]);
					$donnee=$q->fetch(PDO::FETCH_ASSOC);
					if($donnee['id_role']==2)
					{
						return new Administrateur($donnee);
					
					}elseif($donnee['id_role']==1)
					{
						return new Agent($donnee);
					}else
					{
						return false;
					}
					
				}		
			}else
			{
				return false;
			}
			
		}else
		{
			return false;
		}

	}

	public function setDb(PDO $db)
	{
		$this->_db=$db;
	}
}

?>