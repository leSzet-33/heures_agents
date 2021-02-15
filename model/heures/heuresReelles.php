<?php  
class heuresReelles extends heures{

	private $nature;

	public function nature()
	{
		return $this->_nature;
	}

	public function setNature($nature)
	{
		if(is_string($nature)){
			$nature=htmlspecialchars($nature);
			$this->nature=$nature;
		}

	}

}

?>