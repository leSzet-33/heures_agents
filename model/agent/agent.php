<?php

require 'personne.php';

class Agent extends Personne{


	const ROLE=1;

	public function role(){return self::ROLE;}
}

?>