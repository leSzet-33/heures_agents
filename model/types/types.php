<?php  

class Type{

	private $_id;
	private $_type;

	public function __construct(array $type)
	{
		$this->hydrate($type);
	}

	public function id(){ return $this->_id;}
	public function type(){ return $this->_type;}

	public function setId($id)
	{
		$id=(int)$id;
		if($id>0)
		{
			$this->_id=$id;
		}
	}

	public function setType($type)
	{
		if(is_string($type)) 
		{
			$this->_type=$type;
		}
	}

	public function hydrate(array $type)
	{
		foreach ($type as $key =>$value) 
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