<?php  

class PosteManager{

	private $db; 

	public function __construct (PDO $db)
	{
		$this->_db=$db;
	}

	public function add(Poste $poste)
	{
		$q=$this->_db->prepare('INSERT INTO postes(poste) VALUES :poste');
		$q->bindValue(':poste',$poste);
		$q->execute();


	}

	public function delete(Poste $poste)
	{
		$this->_db->exec('DELETE FROM postes WHERE id='.$poste->id());
	}

	public function update(Poste $poste)
	{
		$q=$this->_db->prepare('UPDATE postes SET poste=:poste WHERE id='.$poste->getId());
		$q->bindValue(':poste',$poste);
		$q->execute();
	}
	public function exist($poste)
	{
		if(is_string($poste))
		{
			$q=$this->_db->prepare('SELECT COUNT(*) FROM postes WHERE poste=:poste');
			$q->execute([':poste'=>$poste]);

			return (bool) $q->fetchColumn();
		}
	}

	public function get($poste)
	{
		if (is_string($poste))
		{
			$poste=htmlspecialchars($poste);
			$q=$this->_db->prepare('SELECT id, poste FROM postes WHERE poste=:poste');
			$q->execute([':poste'=>$poste]);

			return new Poste($q->fetch(PDO::FETCH_ASSOC));
		}
	}

	public function getList() 
	{
		$poste=[];

		$q=$this->_db->prepare('SELECT id,poste FROM postes');
		$q->execute();

		$donnees=$q->fetchAll(PDO::FETCH_ASSOC);
		return $donnees;
		/*while ($donnees=$q->fetch(PDO::FETCH_ASSOC));
		{
			$poste= new Poste($donnees);
		}
		
		return $donnees;*/
	} 

	public function setDb($db)
	{
		$this->_db=$db;
	} 

}

?>