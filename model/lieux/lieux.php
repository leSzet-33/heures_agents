<?php

class Lieux {

	private $_id;
	private $_lieu;
	private $_adresse;
	private $_tel;

	public function __construct(array $lieux)
	{
		$this->hydrate($lieux);
	}

	public function id(){return $this->_id;}
	public function lieu(){return $this->_lieu;}
	public function adresse(){return $this->_adresse;}
	public function tel(){return $this->_tel;}

	public function setId($id)
	{
		$id=(int)$id;
		if($id>0)
			{
				$this->_id=$id;
			}
	}

	public function setLieu($lieu)
	{
		if(is_string($lieu)&&(strlen($lieu)<32))
		{
			$this->_lieu=$lieu;
		}
	}

	public function setAdresse($adresse)
	{
		if(is_string($adresse))
		{
			$this->_adresse=$adresse;
		}
	}

	public function setTel($tel)
	{
	$pattern = '/(\+\d+(\s|-))?0\d(\s|-)?(\d{2}(\s
	|-)?){4}/';
	
		if(preg_match($pattern, $tel))
		{
			$this->_tel=$tel;
		}
	}

	public function hydrate(array $lieux)
	{
		foreach ($lieux as $key =>$value) 
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