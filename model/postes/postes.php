<?php  

class Poste{

	private $_id;
	private $poste;

	public function __construct(array $poste)
	{
		$this->hydrate($poste);
	}

	public function id(){ return $this->_id;}
	public function poste(){ return $this->_poste;}

	public function setId($id)
	{
		$id=(int)$id;
		if($id>0)
		{
			$this->_id=$id;
		}
	}

	public function setPoste($poste)
	{
		if(is_string($poste)) 
		{
			$this->_poste=$poste;
		}
	}

	public function hydrate(array $poste)
	{
		foreach ($poste as $key =>$value) 
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