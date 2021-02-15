<?php  

class TypeManager{

	private $db; 

	public function __construct (PDO $db)
	{
		$this->_db=$db;
	}

	public function add(Type $type)
	{
		$q=$this->_db->prepare('INSERT INTO type(type) VALUES :type');
		$q->bindValue(':type',$type);
		$q->execute();


	}

	public function delete(Type $type)
	{
		$this->_db->exec('DELETE FROM type WHERE id='.$type->id());
	}

	public function update(Type $type)
	{
		$q=$this->_db->prepare('UPDATE type SET type=:type WHERE id='.$type->getId());
		$q->bindValue(':type',$type);
		$q->execute();
	}
	public function exist($type)
	{
		if(is_string($type))
		{
			$q=$this->_db->prepare('SELECT COUNT(*) FROM type WHERE type=:type');
			$q->execute([':type'=>$type]);

			return (bool) $q->fetchColumn();
		}
	}

	public function get($type)
	{
		if (is_string($type))
		{
			$type=htmlspecialchars($type);
			$q=$this->_db->prepare('SELECT id, type FROM type WHERE type=:type');
			$q->execute([':type'=>$type]);

			return new Type($q->fetch(PDO::FETCH_ASSOC));
		}
	}

	public function getList() 
	{
		$type=[];

		$q=$this->_db->prepare('SELECT id,type FROM type');
		$q->execute();
		while ($donnees=$q->fetch(PDO::FETCH_ASSOC));
		{
			$type= new Type($donnees);
		}
		
		return $type;
	}

	public function setDb($db)
	{
		$this->_db=$db;
	} 

}

?>