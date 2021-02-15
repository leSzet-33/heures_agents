<?php  

class PeriodeManager{

	private $db; 

	public function __construct (PDO $db)
	{
		$this->_db=$db;
	}

	public function add(Periode $periode)
	{
		$q=$this->_db->prepare('INSERT INTO periode(nomPeriode, dateDebut, dateFin, typePeriode) VALUES (:nomPeriode, :dateDebut, :dateFin, :typePeriode)');
		$q->bindValue(':nomPeriode',$periode->nomPeriode());
		$q->bindValue(':dateDebut',$periode->dateDebut());
		$q->bindValue(':dateFin',$periode->dateFin());
		$q->bindValue(':typePeriode',$periode->typePeriode());
		return $q->execute();



	}

	public function delete(Type $periode)
	{
		$this->_db->exec('DELETE FROM type WHERE id='.$type->id());
	}

	public function update(Type $periode)
	{
		$q=$this->_db->prepare('UPDATE type SET type=:type WHERE id='.$type->getId());
		$q->bindValue(':type',$type);
		$q->execute();
	}
	public function exist($periode)
	{
		if(is_string($type))
		{
			$q=$this->_db->prepare('SELECT COUNT(*) FROM type WHERE type=:type');
			$q->execute([':type'=>$type]);

			return (bool) $q->fetchColumn();
		}
	}

	public function getNomPeriode($jour)
	{
		if (is_string($jour))
		{
			$type=htmlspecialchars($periode);
			$q=$this->_db->prepare('SELECT id, nomPeriode FROM periode WHERE dateDebut<=:dateDebut AND dateFin>=dateDin');
			$q->execute([
				'date_debut'=>$jour,
				'date_fin'=>$jour
			]);

			return new Periode($q->fetch(PDO::FETCH_ASSOC));
		}
	}

	public function get($id)
	{
		if (is_string($id))
		{
			$type=htmlspecialchars($id);
			$q=$this->_db->prepare('SELECT id, nomPeriode, dateDebut, dateFin, typePeriode  FROM periode WHERE id=:id');
			$q->execute([
				'id'=>$id
			]);

			$donnees=$q->fetch(PDO::FETCH_ASSOC);
			return new Periode($donnees);
		}
	}

	public function getList() 
	{
		$periode=[];

		$q=$this->_db->query("SELECT dateDebut, dateFin FROM periode");
		
		return $donnees=$q->fetchall(PDO::FETCH_ASSOC);
	}

	public function setDb($db)
	{
		$this->_db=$db;
	} 

}

?>