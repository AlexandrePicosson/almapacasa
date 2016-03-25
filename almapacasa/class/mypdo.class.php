<?php
class mypdo extends PDO{

    private $PARAM_hote='localhost'; // le chemin vers le serveur
    private $PARAM_utilisateur='root'; // nom d'utilisateur pour se connecter
    private $PARAM_mot_passe=''; // mot de passe de l'utilisateur pour se connecter
    private $PARAM_nom_bd='almapacasa';
    private $connexion;
    public function __construct() {
    	try {
    		
    		$this->connexion = new PDO('mysql:host='.$this->PARAM_hote.';dbname='.$this->PARAM_nom_bd, $this->PARAM_utilisateur, $this->PARAM_mot_passe,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    		//echo '<script>alert ("ok connex");</script>)';echo $this->PARAM_nom_bd;
    	}
    	catch (PDOException $e)
    	{
    		echo 'hote: '.$this->PARAM_hote.' '.$_SERVER['DOCUMENT_ROOT'].'<br />';
    		echo 'Erreur : '.$e->getMessage().'<br />';
    		echo 'N° : '.$e->getCode();
    		$this->connexion=false;
    		//echo '<script>alert ("pbs acces bdd");</script>)';
    	}
    }
    public function __get($propriete) {
    	switch ($propriete) {
    		case 'connexion' :
    			{
    				return $this->connexion;
    				break;
    			}
    	}
    }
    
    public function connect($tab)
    {
		$reponse = array();
    	//Requete patient
    	$requetepatient = 'select * from patient where login="'.$tab['id'].'" and mdp="'.$tab['mdp'].'";';
    	 
    	//Requete Personne de confiance
    	$requetepersonnedc = 'select * from personne_de_confiance where login="'.$tab['id'].'" and mdp="'.$tab['mdp'].'";';
    	
    	//Requete administrateur
    	$requeteadmin = 'select * from administrateur where login="'.$tab['id'].'" and mdp="'.$tab['mdp'].'";';
    	
    	//Requete infirmiere
    	$requeteinfirmiere = 'select * from infirmiere where login="'.$tab['id'].'" and mdp="'.$tab['mdp'].'";';

    	//Réalisation des requetes
    	$result=$this->connexion->query($requetepatient);
		
		//Retour l'administrateur
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			$reponse['result'] = $result;
    			$reponse['type'] = 'patient';
    			return ($reponse);
    		}
    	}
    	
    	$result=$this->connexion->query($requetepersonnedc);
    	//Retour l'infirmiere
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			$reponse['result'] = $result;
    			$reponse['type'] = 'personne_de_confiance';
    			return ($reponse);
    		}
    	}
    	
    	$result=$this->connexion->query($requeteinfirmiere);
    	//Retour le patient
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			$reponse['result'] = $result;
    			$reponse['type'] = 'infirmiere';
    			return ($reponse);
    		}
    	}
    	
    	$result=$this->connexion->query($requeteadmin);
    	//Retour la personne de confiance
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			$reponse['result'] = $result;
    			$reponse['type'] = 'admin';
    			return ($reponse);
    		}
    	}
    	return null;
    }

}
?>
