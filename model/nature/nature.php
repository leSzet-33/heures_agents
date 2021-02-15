<?php  

class Nature{

	private $_id;
	private $_nature;

	public function __construct(array $nature)
	{
		$this->hydrate($nature);
	}

	public function id(){ return $this->_id;}
	public function nature(){ return $this->_nature;}

	public function setId($id)
	{
		$id=(int)$id;
		if($id>0)
		{
			$this->_id=$id;
		}
	}

	public function setNature($nature)
	{
		if(is_string($nature)) 
		{
			$this->_nature=$nature;
		}
	}

	public function hydrate(array $nature)
	{
		foreach ($nature as $key =>$value) 
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