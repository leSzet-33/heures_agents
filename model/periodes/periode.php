<?php  

class Periode{

	private $_id;
	private $_nomPeriode;
	private $_dateDebut;
	private $_dateFin; 
	private $_typePeriode;

	public function __construct(array $periode)
	{
		$this->hydrate($periode);
	}

	public function id(){ return $this->_id;}
	public function nomPeriode(){ return $this->_nomPeriode;}
	public function dateDebut()
	{ 
		$date_debut = new DateTime($this->_dateDebut);
		return $dateDebut=$date_debut->format('Y-m-d');
	}
	public function dateFin()
	{
	 	$date_fin = new DateTime($this->_dateFin);
		return $dateFin=$date_fin->format('Y-m-d');
	}
	public function typePeriode(){ return $this->_typePeriode;}

	public function setId($id)
	{
		$id=(int)$id;
		if($id>0)
		{
			$this->_id=$id;
		}
	}

	public function setNomPeriode($nom)
	{
		if(is_string($nom)) 
		{
			$this->_nomPeriode=$nom;
		}
	}

	public function setDateDebut($debut)
	{
		if(is_string($debut)) 
		{
			$this->_dateDebut=$debut;
		}
	}

	public function setDateFin($fin)
	{
		if(is_string($fin)) 
		{
			$this->_dateFin=$fin;
		}
	}

	public function setTypePeriode($type)
	{
		if(is_string($type)) 
		{
			$this->_typePeriode=$type;
		}
	}

	public function hydrate(array $periode)
	{
		foreach ($periode as $key =>$value) 
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