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
    
    public function connecte_toi($tab)
    {
    	//Requete administrateur
    	$requete = 'select * from administrateur where login="'.$tab['id'].'" and mdp="'.$tab['mdp'].'";';
    	
    	//Requete infirmiere
    	$requete2 = 'select * from infirmiere where login="'.$tab['id'].'" and mdp="'.$tab['mdp'].'";';
    	
    	//Requete patient
    	$requete3 = 'select * from patient where login="'.$tab['id'].'" and mdp="'.$tab['mdp'].'";';
    	
    	//Requete Personne de confiance
    	$requete4 = 'select * from personne_de_confiance where login="'.$tab['id'].'" and mdp="'.$tab['mdp'].'";';
    	
    	//Réalisation des requetes
    	$result=$this->connexion->query($requete);
    	$result2=$this->connexion->query($requete2);
    	$result3=$this->connexion->query($requete3);
    	$result4=$this->connexion->query($requete4);
    	
    	//Retour l'administrateur
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			return ($result);
    		}
    	}
    	//Retour l'infirmiere
    	if($result2)
    	{
    		if($result2->rowCount() == 1)
    		{
    			return ($result2);
    		}
    	}
    	//Retour le patient
    	if($result3)
    	{
    		if($result3->rowCount() == 1)
    		{
    			return ($result3);
    		}
    	}
    	//Retour la personne de confiance
    	if($result4)
    	{
    		if($result4->rowCount() == 1)
    		{
    			return ($result4);
    		}
    	}
    	return null;
    }

}
?>
