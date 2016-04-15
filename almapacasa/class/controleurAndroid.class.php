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
	
}