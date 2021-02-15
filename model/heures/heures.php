<?php  

class heures{
	private $_id;
	private $_jour;
	private $_debut;
	private $_fin;
	/*private $_lieux;
	private $_agent;
	private $_type;
	private $_tache;*/


	public function __construct(array $heures)
	{
		$this->hydrate($heures); 
	}
	public function id(){return $this->_id;}
	public function jour(){return $this->_jour;}
	public function debut(){return $this->_debut;}
	public function fin(){ return $this->_fin;}
	/*public function lieux(){return $this->_lieux;}
	public function agent(){return $this->_agent;}
	public function type(){ return $this->_type;}
	public function tache(){return $this->_tache;}*/

	public function setId($id)
	{
		$this->_id=$id;
	}

	public function setJour($jour)
	{
		$this->_jour=$jour;
	}
	public function setDebut($debut)
	{
		$this->_debut=$debut;
	}
	public function setFin($fin)
	{
		$this->_fin=$fin;
	}
	/*public function setLieux($lieux)
	{
		$this->_lieux=$lieux;
	}
	public function setAgent($agent)
	{
		$this->_agent=$agent;
	}
	public function setType($type)
	{
		$this->_type=$type;
	}

	public function setTache($tache)
	{
		$this->_tache=$tache;
	}*/

	public function hydrate(array $heures)
	{
		foreach ($heures as $key =>$value) 
		{
			$method='set'.ucfirst($key);
			if(method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}

	public function __destruct()
	{
	}
}

?>