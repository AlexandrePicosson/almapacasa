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
    
    //Fonction de connexion
    public function connect($tab)
    {
		$reponse = array();
    	//Requete patient
    	$requetepatient = 'select * from patient where login="'.$tab['id'].'" and mdp=MD5("'.$tab['mdp'].'");';
    	 
    	//Requete Personne de confiance
    	$requetepersonnedc = 'select * from personnedeconfiance where login="'.$tab['id'].'" and mdp=MD5("'.$tab['mdp'].'");';
   
    	//Requete administrateur
    	$requeteadmin = 'select * from administrateur where login="'.$tab['id'].'" and mdp=MD5("'.$tab['mdp'].'");';
    	
    	//Requete infirmiere
    	$requeteinfirmiere = 'select * from infirmiere where login="'.$tab['id'].'" and mdp=MD5("'.$tab['mdp'].'");';

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

///////////// ANDROID - IMPORT - EXPORT ////////////////
    
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
    	$requete = 'select * from infirmiere where login="'.$identifiant.'" and mdp=MD5("'.$mdp.'");';
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
    
    public function importSoin()
    {
    	$requete = 'SELECT * from soin;';
    	 
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
     
    public function importTypeSoin()
    {
    	$requete = 'select * from typesoin;';
    	 
    	$reponse = $this->connexion->query($requete);
    	 
    	if($reponse)
    	{
    		if($reponse->rowCount() >=1)
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
		left join commentaire c on v.id = c.idVisite
		where isNull(c.idPatient)
		and v.dateV > CURDATE()
		and v.dateV > CURDATE()+7; ';
    	 
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
///////////// COMMENTAIRE ////////////////
    //Liste des commentaires
    public function selectCom(){
    	$requete = 'select id from commentaire WHERE idAdmin IS NULL;';
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
    
    //Trouve les commentaires
    public function trouveCommentaire($id)
    {
    	$requete = 'SELECT * from commentaire where id ='.$id.';';
    		
    	$result = $this->connexion->query($requete);
    		
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			$retour = $result->fetch(PDO::FETCH_ASSOC);
    			return $retour;
    		}
    	}
    	return null;
    }
    
    //Valide le commentaire
    public function valide_com($tab){
    	$requete = 'UPDATE commentaire
    			SET idPatient = "'.$tab['idPatient'].'",
    			idVisite = "'.$tab['idVisite'].'",
    			idInfirmiere = "'.$tab['idInfirmiere'].'",
    			idAdmin = "'.$tab['idAdmin'].'",
    			libelle = "'.$tab['libelle'].'"
    			WHERE id = "'.$tab['id'].'";';
    		
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
    		$data['message'] = "des erreurs sont présentes";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre ajout a bien été effectué.";
    	}
    	return $data;
    }
    
///////////// SOIN //////////////// 
	//Affiche la liste des soins
	public function trouveSoinRDV($tab){
		$requete = 'SELECT * from comprendre;';
		 
		$result = $this->connexion->query($requete);
		 
		if($result)
		{
			if($result->rowCount() == 1)
			{
				$retour = $result->fetch(PDO::FETCH_ASSOC);
				return $retour;
			}
		}
		return null;
	}
	
	//Insertion d'un soin pour une visite
	public function insert_soinRDV($tab){
		$requete = 'INSERT INTO `comprendre` (`idVisite`, `idSoin`) VALUES ("'.$tab['idVisite'].'", "'.$tab['idSoin'].'");';
		 
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
			$data['message'] = "des erreurs sont présentes";
		} else {
			$data['success'] = true;
			$data['message'] = "Votre ajout a bien été effectué.";
		}
		return $data;
	}
	
	//Modification d'un soin pour une visite
	public function update_soinRDV($tab){
		$requete = 'UPDATE comprendre
    			SET idVisite = "'.$tab['idVisite'].'",
    			idSoin = "'.$tab['idSoin'].'"
    			WHERE idVisite = "'.$tab['idVisiteSoin'].'";';
			
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
			$data['message'] = "des erreurs sont présentes";
		} else {
			$data['success'] = true;
			$data['message'] = "Votre ajout a bien été effectué.";
		}
		return $data;
	}
	
	//Suppression d'un soin à une visite
	public function suppr_soinRDV($tab){
		$requete = 'DELETE FROM comprendre
				WHERE idVisite = '.$tab['idVisiteSoin'].'';
		
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
			$data['message'] = "Des erreurs sont présentes.";
		} else {
			$data['success'] = true;
			$data['message'] = "Votre suppression a bien été effectué.";
		}
		
		return $data;
	}
	
	//Affiche la liste des id des visites
	public function recupListeVisite(){
		$requete = 'SELECT id from visite;';
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
	
	//Affiche la liste des soins
	public function recupListeSoin(){
		$requete = 'SELECT * from soin;';
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
	
	//Affiche les soins affectés
	public function selectsoinRDV(){
		$requete = 'select idVisite from comprendre;';
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
    
    
///////////// TEMOIGNAGE ////////////////  
	//Liste des témoignages non validés
	public function afficheTemoignageDB(){
		$requete = 'select * from temoignage where idAdmin IS NOT NULL;';
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
	
	//Liste des témoignages
	public function selectTem(){
		$requete = 'select id from temoignage WHERE idAdmin IS NULL;';
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
	
	//Trouve les temoignages
	public function trouveTemoignage($id)
	{
		$requete = 'SELECT * from temoignage where id ='.$id.';';
		 
		$result = $this->connexion->query($requete);
		 
		if($result)
		{
			if($result->rowCount() == 1)
			{
				$retour = $result->fetch(PDO::FETCH_ASSOC);
				return $retour;
			}
		}
		return null;
	}
	
	//Valide un temoignage (admin)
	public function valide_tem($tab){
		$requete = 'UPDATE temoignage
    			SET idPatient = "'.$tab['idPatient'].'",
    			idAdmin = "'.$tab['idAdmin'].'",
    			libelle = "'.$tab['libelle'].'"
    			WHERE id = "'.$tab['id'].'";';
		 
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
    		$data['message'] = "des erreurs sont présentes";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre ajout a bien été effectué.";
    	}
    		return $data;
	}
	
	//Ajoute un nouveau témoignage (patient)
	public function valide_ajout_tem($tab){
		$requete = 'INSERT INTO `temoignage` (`id`, `idPatient` , `idAdmin`, `libelle`) VALUES (NULL, "'.$tab['id'].'",NULL,"'.$tab['libelleTem'].'");';
		
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
			$data['message'] = "des erreurs sont présentes";
		} else {
			$data['success'] = true;
			$data['message'] = "Votre ajout a bien été effectué.";
		}
		return $data;
	}
	
    
    
///////////// PATIENT ////////////////    
    
    //Ajoute un patient
    public function insert_patient_admin($tab)
    {
    	$requete = 'INSERT INTO `patient` (`id`, `idPersonneDeConf`, `nom`, `prenom`, `login`, `mdp`, `anNaiss`, `sexe`, `rue`, `cp`, `ville`, `telephone`) VALUES (NULL, \'1\',"'.$tab['nom'].'", "'.$tab['prenom'].'", "'.$tab['login'].'", "'.$tab['mdp'].'", "'.$tab['anNaiss'].'", "'.$tab['sexe'].'", "'.$tab['rue'].'", "'.$tab['cp'].'", "'.$tab['ville'].'", "'.$tab['telephone'].'");';
    	
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
    		$data['message'] = "des erreurs sont présentes";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre ajout a bien été effectué.";
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
    
    //Trouve les patients
    public function trouvePatient($id)
    {
    	$requete = 'SELECT * from patient where id ='.$id.';';
    	
    	$result = $this->connexion->query($requete);
    	
	    if($result)
	   	{
	    	if($result->rowCount() == 1)
	    	{
	    		$retour = $result->fetch(PDO::FETCH_ASSOC);
	    		return $retour;
	    	}
	    }
	    return null;
    }
    
    //Modifie un patient
    public function update_patient_admin($tab)
    {
    	$requete = 'UPDATE patient 
    			SET nom = "'.$tab['nom'].'",
    			prenom = "'.$tab['prenom'].'",
    			anNaiss = "'.$tab['anNaiss'].'",
    			sexe = "'.$tab['sexe'].'",
    			rue = "'.$tab['rue'].'",
    			cp = "'.$tab['cp'].'",
    			ville = "'.$tab['ville'].'",
    			telephone = "'.$tab['telephone'].'"
    			WHERE id = "'.$tab['id'].'"';
    	
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
    		$data['message'] = "Des erreurs sont presentes.";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre mise à jour a bien ete effectue.";
    	}
    	
    	return $requete;
    }
    
    //Supprime un patient
    public function delete_patient_admin($tab){
    	$requete = 'DELETE FROM PATIENT
				WHERE id = '.$tab['id'].'';
    	 
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
    		$data['message'] = "Des erreurs sont présentes.";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre suppression a bien été effectué.";
    	}
    	 
    	return $data;
    }
    
///////////// INFIRMIERE ////////////////
    
    //Ajoute une infirmiere
    public function insert_infirmiere_admin($tab){
    	$requete = 'INSERT INTO `infirmiere` (`id`, `urlPhoto` , `nom`, `prenom`, `login`, `mdp`, `anNaiss`, `sexe`, `rue`, `cp`, `ville`, `telephone`) VALUES (NULL, "'.$tab['urlphoto'].'","'.$tab['nom'].'", "'.$tab['prenom'].'", "'.$tab['login'].'", "'.$tab['mdp'].'", "'.$tab['anNaiss'].'", "'.$tab['sexe'].'", "'.$tab['rue'].'", "'.$tab['cp'].'", "'.$tab['ville'].'", "'.$tab['telephone'].'");';
    	 
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
    		$data['message'] = "des erreurs sont présentes";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre ajout a bien été effectué.";
    	}
    	return $data;
    }
    
    //Recupération de l'infirmiere à récuperer
    public function modifInfirmiereRecupDB(){
    
    	$requete = 'select id,nom,prenom from infirmiere';
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
    
    //Trouve les infirmieres
    public function trouveInfirmiere($id){
    	$requete = 'SELECT * from infirmiere where id ='.$id.';';
    	 
    	$result = $this->connexion->query($requete);
    	 
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			$retour = $result->fetch(PDO::FETCH_ASSOC);
    			return $retour;
    		}
    	}
    }
    
    //Modifie une infirmiere
    public function update_infirmiere_admin($tab){
    	$requete = 'UPDATE infirmiere
    			SET nom = "'.$tab['nom'].'",
    			urlPhoto = "'.$tab['urlphoto'].'",
    			prenom = "'.$tab['prenom'].'",
    			anNaiss = "'.$tab['anNaiss'].'",
    			sexe = "'.$tab['sexe'].'",
    			rue = "'.$tab['rue'].'",
    			cp = "'.$tab['cp'].'",
    			ville = "'.$tab['ville'].'",
    			telephone = "'.$tab['telephone'].'"
    			WHERE id = "'.$tab['id'].'"';
    	 
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
    		$data['message'] = "Des erreurs sont presentes.";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre mise à jour a bien ete effectue.";
    	}
    	 	
    	return $data;
    }
    
    //Supprimer une infirmiere
    public function delete_infirmiere_admin($tab){
    	$requete = 'DELETE FROM INFIRMIERE
				WHERE id = '.$tab['id'].'';
    	
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
    		$data['message'] = "Des erreurs sont présentes.";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre suppression a bien été effectué.";
    	}
    	
    	return $data;
    }
    
    
    
///////////// ADMINISTRATEUR ////////////////    
    
    //Recupere les informations personnelles d'un administrateur
    public function trouveModifAdmin($id){
    	$requete = 'SELECT * from administrateur where id ='.$id.';';
    	
    	$result = $this->connexion->query($requete);
    	
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			$retour = $result->fetch(PDO::FETCH_ASSOC);
    			return $retour;
    		}
    	}
    }
    
///////////// PERSONNE DE CONFIANCE ////////////////    
    
    //Recupere les informations personnelles d'une personne de confiance
    public function trouveModifPersonneC($id){
    	$requete = 'SELECT * from personnedeconfiance where id ='.$id.';';
    	 
    	$result = $this->connexion->query($requete);
    	 
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			$retour = $result->fetch(PDO::FETCH_ASSOC);
    			return $retour;
    		}
    	}
    }
    
    //Récupere la liste des personnes de confiance
    public function modifPersonneCRecupDB(){
    	$requete = 'select id,nom,prenom from personnedeconfiance';
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
    
    //Trouve les personnes de confiance
    public function trouvePersonneC($id){
    	$requete = 'SELECT * from personnedeconfiance where id ='.$id.';';
    
    	$result = $this->connexion->query($requete);
    
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			$retour = $result->fetch(PDO::FETCH_ASSOC);
    			return $retour;
    		}
    	}
    }
    
    
    //Ajout d'une personne de confiance
    public function insert_personneC_admin($tab){
    	$requete = 'INSERT INTO `personnedeconfiance` (`id`, `nom`, `prenom`, `login`, `mdp`, `anNaiss`, `sexe`, `rue`, `cp`, `ville`, `telephone`) VALUES (NULL,"'.$tab['nom'].'", "'.$tab['prenom'].'", "'.$tab['login'].'", "'.$tab['mdp'].'", "'.$tab['anNaiss'].'", "'.$tab['sexe'].'", "'.$tab['rue'].'", "'.$tab['cp'].'", "'.$tab['ville'].'", "'.$tab['telephone'].'");';
    
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
    		$data['message'] = "des erreurs sont présentes";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre ajout a bien été effectué.";
    	}
    	return $data;
    }
    
    //Modifie une personne de confiance
    public function update_personneC_admin($tab){
    	$requete = 'UPDATE personnedeconfiance
    			SET nom = "'.$tab['nom'].'",
    			prenom = "'.$tab['prenom'].'",
    			anNaiss = "'.$tab['anNaiss'].'",
    			sexe = "'.$tab['sexe'].'",
    			rue = "'.$tab['rue'].'",
    			cp = "'.$tab['cp'].'",
    			ville = "'.$tab['ville'].'",
    			telephone = "'.$tab['telephone'].'"
    			WHERE id = "'.$tab['id'].'"';
    
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
    		$data['message'] = "Des erreurs sont presentes.";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre mise à jour a bien ete effectue.";
    	}
    	 
    	return $data;
    }
    
    //Supprimer une personne de confiance
    public function delete_personneC_admin($tab){
    	$requete = 'DELETE FROM personnedeconfiance
				WHERE id = '.$tab['id'].'';
    	 
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
    		$data['message'] = "Des erreurs sont présentes.";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre suppression a bien été effectué.";
    	}
    	 
    	return $data;
    }

///////////// INFORMATIONS PERSONNELLES ////////////////    
    
    //Modifier mes informations personnelles ( admin )
    public function modif_infosperso_admin($tab){
    	$requete = 'UPDATE administrateur
    			SET nom = "'.$tab['nom'].'",
    			prenom = "'.$tab['prenom'].'",
    			anNaiss = "'.$tab['anNaiss'].'",
    			sexe = "'.$tab['sexe'].'",
    			rue = "'.$tab['rue'].'",
    			cp = "'.$tab['cp'].'",
    			ville = "'.$tab['ville'].'",
    			telephone = "'.$tab['telephone'].'"
    			WHERE id = "'.$tab['id'].'"';
    	
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
    		$data['message'] = "Des erreurs sont presentes.";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre mise à jour a bien ete effectue.";
    	}
    	
    	return $data;
    }
    
    //Modifier infos perso personne de confiance
    public function modif_infosperso_personneC($tab){
    	$requete = 'UPDATE personnedeconfiance
    			SET nom = "'.$tab['nom'].'",
    			prenom = "'.$tab['prenom'].'",
    			anNaiss = "'.$tab['anNaiss'].'",
    			sexe = "'.$tab['sexe'].'",
    			rue = "'.$tab['rue'].'",
    			cp = "'.$tab['cp'].'",
    			ville = "'.$tab['ville'].'",
    			telephone = "'.$tab['telephone'].'"
    			WHERE id = "'.$tab['id'].'"';
    
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
    		$data['message'] = "Des erreurs sont presentes.";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre mise à jour a bien ete effectue.";
    	}
    
    	return $data;
    }
 
///////////// RENDEZ - VOUS //////////////// 
    
    //Trouve les rendez_vous
    public function trouveRDV($id){
    	$requete = 'SELECT * from visite where id ='.$id.';';
    
    	$result = $this->connexion->query($requete);
    
    	if($result)
    	{
    		if($result->rowCount() == 1)
    		{
    			$retour = $result->fetch(PDO::FETCH_ASSOC);
    			return $retour;
    		}
    	}
    }
    
    //Affiche tout les id des rendez - vous
    public function selectRDV(){
    	$requete = 'select id from visite';
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
    
    //Ajoute un rendez-vous
    public function insert_RDV_admin($tab){
    	$requete = 'INSERT INTO `visite` (`id`, `idPatient`, `idInfirmiere`, `dateV`, `heureDebut`, `heureFin`) VALUES (NULL,"'.$tab['idPatient'].'", "'.$tab['idInfirmiere'].'", "'.$tab['dateVisite'].'", "'.$tab['heureDeb'].'", "'.$tab['heureFin'].'");';
    	
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
    		$data['message'] = "des erreurs sont présentes";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre ajout a bien été effectué.";
    	}
    	return $data;
    }
    
    //Modifie un RDV
    public function update_RDV_admin($tab){
    	$requete = 'UPDATE visite
    			SET idPatient = "'.$tab['idPatient'].'",
    			idInfirmiere = "'.$tab['idInfirmiere'].'",
    			dateV = "'.$tab['dateVisite'].'",
    			heureDebut = "'.$tab['heureDeb'].'",
    			heureFin = "'.$tab['heureFin'].'"
    			WHERE id="'.$tab['id'].'"';
    	 
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
    		$data['message'] = "Des erreurs sont presentes.";
    	} else {
    		$data['success'] = true;
    		$data['message'] = "Votre mise à jour a bien ete effectue.";
    	}
    	 
    	return $data;
    	}
    
   	//Supprime un RDV
   	public function delete_RDV_admin($tab){
   		$requete = 'DELETE FROM visite
				WHERE id = '.$tab['id'].'';
   		
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
   			$data['message'] = "Des erreurs sont présentes.";
   		} else {
   			$data['success'] = true;
   			$data['message'] = "Votre suppression a bien été effectué.";
   		}
   		
   		return $data;
   	}
	
	public function update_rdv_android($tab){
		$requete = 'update visite
					set heureDebut= "'.$tab['heureD'].'",
					heureFin = "'.$tab['heureF'].'"
					where id="'.$tab['id'].'"';
		$nblignes=$this->connexion -> exec($requete);
		
		if($nblignes != 1 ){
			return false;
		}
		
		return true;
	}

	public function update_soins_android($id, $var){
		$requete = 'insert into effectue values('.$id.','.$var.')';
		$nblignes=$this->connexion -> exec($requete);
		if($nblignes != 1 ){
			return false;
		}
		return true;
	}

	public function add_commentaire_android($idVisite, $idInfi, $content){
		$requete = 'select * from commentaire where idVisite ="'.$idVisite.'" and idInfirmiere ="'.$idInfi.'";';
		$reponse = $this->connexion->query($requete);

		if($reponse)
		{
			if($reponse->rowCount() != 1)
			{
				$requete = 'insert into commentaire (idVisite, idInfirmiere, libelle) values('.$idVisite.','.$idInfi.','.$content.');';
				$nblignes=$this->connexion->exec($requete);
			}
			else
			{
				$requete = 'update commentaire
							set libelle ="'.$content.'"
							where idVisite ="'.$idVisite.'"
							and idInfirmiere ="'.$idInfi.'";';
				$nblignes=$this->connexion->exec($requete);
			}
		}
	}
}
?>
