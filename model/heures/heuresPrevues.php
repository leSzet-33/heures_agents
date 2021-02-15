<?php  
require 'heures.php';

class HeuresPrevues extends heures{

	private $_validee = 0;

	public function getValidee(){return $this->_validee;}

	public function valider()
	{
		$this->_validee=1;
	}



}

?>