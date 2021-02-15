<?php  

class LieuxManager{

	private $db;

	public function __construct (PDO $db)
	{
		$this->_db=$db;
	}

	public function add(Lieux $lieux)
	{
		$q=$this->_db->prepare('INSERT INTO lieux(lieu, adresse, tel) VALUES (:lieu, :adresse, :tel);');
		$q->bindValue(':lieu', $lieux->lieu());
		$q->bindValue(':adresse', $lieux->adresse());
		$q->bindValue(':tel', $lieux->tel());

		$q->execute();

		return true;


	}

	public function delete(Lieux $lieux)
	{
		$this->_db->exec('DELETE FROM lieux WHERE id='.$lieux->id());
		return true;
	}

	public function update(Lieux $lieux)
	{
		$q=$this->_db->prepare('UPDATE lieux SET lieu=:lieu, adresse=:adresse, tel=:telephone WHERE id='.$lieux->id());

		$q->bindValue(':lieu', $lieux->lieu());
		$q->bindValue(':adresse', $lieux->adresse());
		$q->bindValue(':telephone', $lieux->tel());

		$q->execute();
		return true;
	}

	public function exist($lieu)
	{
		if(is_string($lieu))
		{
			$q=$this->_db->prepare('SELECT COUNT(*) FROM lieux WHERE lieu=:lieu');
			$q->execute([':lieu'=>$lieu]);

			return (bool) $q->fetchColumn();
		}
	}

	public function get($lieu)
	{

		if (is_string($lieu))
		{
			$lieu=strtolower($lieu);
			$lieu=htmlspecialchars($lieu);
			$q=$this->_db->prepare('SELECT id, lieu, adresse, tel FROM lieux WHERE lieu=LOWER(:lieu);');
			$q->execute([':lieu'=>$lieu]);

			return new Lieux($q->fetch(PDO::FETCH_ASSOC));
		}
	}

	public function getList()
	{
		$q=$this->_db->query('SELECT lieu FROM lieux ORDER BY lieu;');
		
		return $donnees=$q->fetchAll(PDO::FETCH_ASSOC);
	}

	public function setDb($db)
	{
		$this->_db=$db;
	} 

}

?>