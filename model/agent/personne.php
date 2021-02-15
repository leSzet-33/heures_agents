<?php

class Personne{

	private $_id;
	private $_nom;
	private $_prenom;
	private $_pass;
	private $_mail;
	private $_poste;
	private $_role;

	public function __construct(array $personne)
	{
		$this->hydrate($personne);
	}

	public function id(){return $this->_id;}
	public function nom(){return $this->_nom;}
	public function prenom(){return $this->_prenom;}
	public function pass(){return $this->_pass;}
	public function mail(){return $this->_mail;}
	public function poste(){return $this->_poste;}
	public function roleId(){return $this->_roleId;}
	
	public function setId($id)
	{
		$id=(int)$id;
		if($id>0)
			{
				$this->_id=$id;
			}
	}

	public function setNom($nom)
	{
		if(is_string($nom)&&(strlen($nom)<32))
		{
			$this->_nom=htmlspecialchars($nom);
		}
	}

	public function setPrenom($prenom)
	{
		if(is_string($prenom)&&(strlen($prenom)<32))
		{
			$this->_prenom=htmlspecialchars($prenom);
		}
	}

	public function setPass($pass)
	{
		if(is_string($pass)&&(strlen($pass)<255))
		{
			$this->_pass=$pass;
		}
	}

	public function setMail($mail)
	{
		$pattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,18}$/";
		if(is_string($mail)&&(strlen($mail)<50))
		{
			if(preg_match($pattern, $mail))
			{
				$this->_mail=$mail;
			}
			else
			{
				echo 'Le format de l\'adresse mail est invalide';
			}
		}
		else
		{
			echo'L\'adresse mail doit être une chaine de caractère de moins de 50 caractère';
		}
	}

	public function setPoste($poste)
	{
		if(is_string($poste)&&(strlen($poste)<32))
		{
			$this->_poste=$poste;
		}
	}

	public function setRoleId($idRole)
	{
		$idRole=(int)$idRole;
		if($idRole>0)
			{
				$this->_idRole=$idRole;
			}
	}

	public function hydrate(array $personne)
	{
		foreach ($personne as $key =>$value) 
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