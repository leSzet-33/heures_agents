<?php  

class Tache{

	private $_id;
	private $_tache; 

	public function __construct(array $tache)
	{
		$this->hydrate($tache);
	}

	public function id(){ return $this->_id;}
	public function tache(){ return $this->_tache;}

	public function setId($id)
	{
		$id=(int)$id;
		if($id>0)
		{
			$this->_id=$id;
		}
	}

	public function setTache($tache)
	{
		if(is_string($tache))
		{
			$this->_tache=$tache;
		}
	}

	public function hydrate(array $tache)
	{
		foreach ($tache as $key =>$value) 
		{
			$method='set'.ucfirst($key);
			if(method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}
	
}

?>