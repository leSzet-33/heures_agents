<?php  

class NatureManager{

	private $db; 

	public function __construct (PDO $db)
	{
		$this->_db=$db;
	}

	public function add(Nature $nature)
	{
		$q=$this->_db->prepare('INSERT INTO nature(nature) VALUES :nature');
		$q->bindValue(':nature',$nature);
		$q->execute();


	} 

	public function delete(Nature $nature)
	{
		$this->_db->exec('DELETE FROM nature WHERE id='.$nature->id());
	}

	public function update(Nature $nature)
	{
		$q=$this->_db->prepare('UPDATE nature SET nature=:nature WHERE id='.$nature->getId());
		$q->bindValue(':nature',$nature);
		$q->execute();
	}
	public function exist($nature)
	{
		if(is_string($nature))
		{
			$q=$this->_db->prepare('SELECT COUNT(*) FROM nature WHERE nature=:nature');
			$q->execute([':nature'=>$nature]);

			return (bool) $q->fetchColumn();
		}
	}

	public function get($nature)
	{
		if (is_string($nature))
		{
			$nature=htmlspecialchars($nature);
			$q=$this->_db->prepare('SELECT id, nature FROM nature WHERE nature=:nature');
			$q->execute([':nature'=>$nature]);

			return new Nature($q->fetch(PDO::FETCH_ASSOC));
		}
	}

	public function getList() 
	{
		$nature=[];

		$q=$this->_db->prepare('SELECT id,nature FROM nature');
		$q->execute();
		while ($donnees=$q->fetch(PDO::FETCH_ASSOC));
		{
			$nature= new Nature($donnees);
		}
		
		return $nature; 
	}

	public function setDb($db)
	{
		$this->_db=$db;
	} 

}

?>