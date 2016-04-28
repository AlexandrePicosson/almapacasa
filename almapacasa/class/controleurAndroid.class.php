<?php
class ControleurAndroid{
	private $mypdo;
	private $db;
	
	public function __construct() {
		$this->mypdo = new mypdo();
		$this->db = $this->mypdo->conexion;
	}
	
	public function __get($propriete)
	{
		switch ($propriete)
		{
			case 'mypdo' :
				{
					return $this->mypdo;
					break;
				}
			case 'db' : 
				{
					return $this->db;
					break;
				}
		}
	}


	
	public function import()
	{
		$utf8Encode = function($n) {	return (utf8_encode($n));	};
		$json = array();
		$result = $this->mypdo->importAndroid();
		if($result)
		{
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$json[] = array_map($utf8Encode, $row);
			}
		}
		echo json_encode($json);
	}
	
	public function login($identifiant, $mdp)
	{
		$utf8Encode = function($n) { return (utf8_encode($n)); };
		$json = array();
		$result = $this->mypdo->loginAndroid($identifiant, $mdp);
		
		if($result && $result != null)
		{
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$json[] = array_map($utf8Encode, $row);
			}
			echo json_encode($json);
		}
		echo json_encode(null);
	}
	
	public function importData()
	{
		$utf8Encode = function($n) { return (utf8_encode($n)); };
		$json = array();
		$data = array();
		$lesSoin = array();
		$lesTypes = array();
		
		$result = $this->mypdo->importDataAndroid();
		
		if($result && $result != null)
		{
			while($visite = $result->fetch(PDO::FETCH_ASSOC))
			{
				$id = $visite['id'];
				$resultSoin = $this->mypdo->importSoinVisite($id);
				$dataSoin = array();
				if($resultSoin && $resultSoin != null)
				{
					while($leSoin = $resultSoin->fetch(PDO::FETCH_ASSOC))
					{
						$dataSoin[] = array_map($utf8Encode, $leSoin);
					}
				}
				$patient = array('nom' => $visite['nom'], 'prenom' => $visite['prenom'], 'adresse' => $visite['rue']." ".$visite['cp']." ".$visite['ville'], 'numero' => $visite['numero']);
				$heure = $visite['heuredebut'];
				$heurefin = $visite['heurefin'];
				$commentaire = $visite['commentaire'];
				$date = $visite['date'];
				$finalObject = array('id' => $id, 'soins' => $dataSoin, 'patient' => $patient, 'heureDebut' => $heure, 'heureFin' => $heurefin, 'commentaire' => $commentaire, 'date' => $date);
				$data[] = $finalObject;
			}
		}
		
		$result = $this->mypdo->importSoin();
		
		if($result && $result != null)
		{
			while($soin = $result->fetch(PDO::FETCH_ASSOC))
			{
				$id = $soin['id'];
				$idTypeSoin = $soin['idTypeSoin'];
				$libelle = $soin['libelle'];
				$finalSoin = array('id' => $id, 'idTypeSoin' => $idTypeSoin, 'libelle' => $libelle);
				$dataSoin[] = $finalSoin;
			}
		}

		$result = $this->mypdo->importTypeSoin();
		
		if($result && $result != null)
		{
			while($typeSoin = $result->fetch(PDO::FETCH_ASSOC))
			{
				$id = $typeSoin['id'];
				$libelle = $typeSoin['libelle'];
				$finalSoin = array('id' => $id, 'libelle' => $libelle);
				$lesTypes[] = $finalSoin;
			}
		}

		$json[] = $data;
		$json[] = $dataSoin;
		$json[] = $lesTypes;
		echo json_encode($json);
	}
	
}