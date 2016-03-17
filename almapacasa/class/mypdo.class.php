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
    if($tab['categ']=='famille'){	
    	$requete='select * from infirmiere where id="'.$tab['id'].'" and mdp=("'.$tab['mp'].'");';
    }
    else{
    	$requete='select * from administrateur where id="'.$tab['id'].'" and mdp=("'.$tab['mp'].'");';
    }
    	$result=$this->connexion ->query($requete);
    	if ($result)
    
    	{
    		if ($result-> rowCount()==1)
    		{
    			return ($result);
    		}
    	}
    	return null;
    }
    
    public function connecte_toi($tab)
    {
    	$requete = 'select * from administrateur where login="'.$tab['id'].'" and mdp="'.$tab['mdp'].'";';
    	$result=$this->connexion->query($requete);
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			return ($result);
    		}
    	}
    	return null;
    }
    
    
    public function trouve_famille($idfamille)
    {
    	$requete='select * from famille where id_famille='.$idfamille.';';
    	$result=$this->connexion ->query($requete);
    	if ($result)
    
    	{
    		if ($result-> rowCount()==1)
    		{
    			return ($result->fetch(PDO::FETCH_OBJ));
    		}
    	}
    	return null;
    }
    

}
?>
