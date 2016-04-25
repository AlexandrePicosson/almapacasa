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

    public function importAndroid()
    {
    	$requete = 'select * from patient limit 1;';
    	$result = $this->connexion->query($requete);
    	if($result)
    	{
    		if($result->rowCount() >= 1)
    		{
    			return ($result);
    		}
    	}
    	return null;
    }
    
    public function loginAndroid($identifiant, $mdp)
    {
    	$requete = 'select * from infirmiere where login="'.$identifiant.'" and mdp="'.$mdp.'";';
    	$result = $this->connexion->query($requete);
    	if($result)
    	{
    		if($result->rowCount() >= 1)
    		{
    			return ($result);
    		}
    	}
    	return null;
    }
    
    //Ajouter un patient
    public function AddPatientDB($tab1){
    	$requete = 'INSERT INTO PATIENT (idPersonneDeConf,prenom,nom,login,mdp,annaiss,sexe,rue,cp,ville,telephone) VALUES ('.$tab1['id_her'].',"'.$tab1['prenom'].'","'.$tab1['nom'].'","'.$tab1['login'].'","'.$tab1['mdp'].'","'.$tab1['dateNaiss'].'","'.$tab1['sexe'].'","'.$tab1['rue'].'",'.$tab1['cp'].',"'.$tab1['ville'].'","'.$tab1['tel'].'");';
    	
    	$nblignes=$this->connexion -> exec($requete);
    	
    	$data = array();
    	$errors = array();
    	
    	if ($nblignes !=1)
    	{
    		$errors['requete']='Pas de modifications d\'information :'.$requete.' nblignes:'.$nblignes;
    	}
    	
    	if (count($errors) > 0) {
    		$data['success'] = false;
    		$data['errors']  = $errors;
    		$data['message'] = header("location:../erreurAjout.php");
    	} else {
    		$data['success'] = true;
    		$data['message'] = header("location:../valideAjout.php");
    	}
    	return $data;
    }
    
    //Ajouter une infirmiere
    public function AddInfirmiereDB($tab1){
    	$requete = 'INSERT INTO INFIRMIERE (urlphoto,prenom,nom,login,mdp,annaiss,sexe,rue,cp,ville,telephone) VALUES ("'.$tab1['urlphoto'].'","'.$tab1['prenom'].'","'.$tab1['nom'].'","'.$tab1['login'].'","'.$tab1['mdp'].'","'.$tab1['dateNaiss'].'","'.$tab1['sexe'].'","'.$tab1['rue'].'",'.$tab1['cp'].',"'.$tab1['ville'].'","'.$tab1['tel'].'");';
    	 var_dump($requete);
    	$nblignes=$this->connexion -> exec($requete);
    	 
    	$data = array();
    	$errors = array();
    	 
    	if ($nblignes !=1)
    	{
    		$errors['requete']='Pas de modifications d\'information :'.$requete.' nblignes:'.$nblignes;
    		echo 'marche pas';
    	}
    	 
    	if (count($errors) > 0) {
    		$data['success'] = false;
    		$data['errors']  = $errors;
    		$data['message'] = header("location:../erreurAjout.php");
    	} else {
    		$data['success'] = true;
    		$data['message'] = header("location:../valideAjout.php");
    	}
    	return $data;
    }
    
    //Ajouter une personne de confiance
    public function AddPersonneCDB($tab1){
    	$requete = 'INSERT INTO personnedeconfiance (prenom,nom,login,mdp,annaiss,sexe,rue,cp,ville,telephone) VALUES ("'.$tab1['prenom'].'","'.$tab1['nom'].'","'.$tab1['login'].'","'.$tab1['mdp'].'","'.$tab1['dateNaiss'].'","'.$tab1['sexe'].'","'.$tab1['rue'].'",'.$tab1['cp'].',"'.$tab1['ville'].'","'.$tab1['tel'].'");';
    	var_dump($requete);
    	$nblignes=$this->connexion -> exec($requete);
    	
    	$data = array();
    	$errors = array();
    	
    	if ($nblignes !=1)
    	{
    		$errors['requete']='Pas de modifications d\'information :'.$requete.' nblignes:'.$nblignes;
    	}
    	
    	if (count($errors) > 0) {
    		$data['success'] = false;
    		$data['errors']  = $errors;
    		$data['message'] = header("location:../erreurAjout.php");
    	} else {
    		$data['success'] = true;
    		$data['message'] = header("location:../valideAjout.php");
    	}
    	return $data;
    }
    
    //Recupération du patient à modifier
    public function modifPatientRecupDB(){
    	
    	$requete = 'select id,nom,prenom from patient';
    	$reponse = $this->connexion->query($requete);// Requête SQL
    	if($reponse)
    	{
    		if($reponse->rowCount() >= 1)
    		{
    			return ($reponse);
    		}
    	}
    	return null;
    }
    
    public function importDataAndroid(){
    	 
    	$requete = 'select v.id, nom, prenom, rue, cp, ville, telephone as numero, heureDebut as heuredebut, heureFin as heurefin, dateV as date, c.libelle as commentaire
		from visite v
		inner join patient p on v.idPatient = p.id
		inner join commentaire c on v.id = c.idVisite
		where isNull(c.idPatient)
		and v.dateV > CURDATE(); ';
    	 
    	$reponse = $this->connexion->query($requete);
    	if($reponse)
    	{
    		if($reponse->rowCount() >= 1)
    		{
    			return ($reponse);
    		}
    	}
    	return null;
    }
    
    public function  importSoinVisite($id){
    	 
    	$requete = 'select idSoin
		from comprendre
		where idVisite ='.$id.';';
    	 
    	$reponse = $this->connexion->query($requete);
    	if($reponse)
    	{
    		if($reponse->rowCount() >= 1)
    		{
    			return ($reponse);
    		}
    	}
    	return null;
    }
}
?>
