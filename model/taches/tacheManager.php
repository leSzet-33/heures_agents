<?php  

class TacheManager{ 

	private $db;

	public function __construct (PDO $db)
	{
		$this->_db=$db;
	}

	public function add(Tache $tache)
	{
		$q=$this->_db->prepare('INSERT INTO tache(tache) VALUES :tache');
		$q->bindValue(':tache',$tache);
		$q->execute();


	}

	public function delete(Tache $tache)
	{
		$this->_db->exec('DELETE FROM tache WHERE id='.$tache->id());
	}

	public function update(Tache $tache)
	{
		$q=$this->_db->prepare('UPDATE tache SET tache=:tache WHERE id='.$tache->getId());
		$q->bindValue(':tache',$tache);
		$q->execute();
	}
	public function exist($tache)
	{
		if(is_string($tache))
		{
			$q=$this->_db->prepare('SELECT COUNT(*) FROM tache WHERE tache=:tache');
			$q->execute([':tache'=>$tache]);

			return (bool) $q->fetchColumn();
		}
	}

	public function get($tache)
	{
		if (is_string($tache))
		{
			$tache=htmlspecialchars($tache);
			$q=$this->_db->prepare('SELECT id, tache FROM tache WHERE tache=:tache');
			$q->execute([':tache'=>$tache]);

			return new Tache($q->fetch(PDO::FETCH_ASSOC));
		}
	}

	public function getList()
	{
		$tache=[];

		$q=$this->_db->query('SELECT id, tache, service FROM tache ORDER BY id');
		
		return $donnees=$q->fetchAll(PDO::FETCH_ASSOC);
		
	}

	public function setDb($db)
	{
		$this->_db=$db;
	} 

}

?>