<?php
class controleur {

	private $vpdo;
	private $db;
	public function __construct() {
		$this->vpdo = new mypdo ();
		$this->db = $this->vpdo->connexion;
	}
	public function __get($propriete) {
		switch ($propriete) {
			case 'vpdo' :
				{
					return $this->vpdo;
					break;
				}
			case 'db' :
				{
					
					return $this->db;
					break;
				}
		}
	}
	

	public function retourne_formulaire_login() {
		return '
			<article >
				<h3>Formulaire de connexion</h3>
				<form id="login" method="post" class="login">
					<input type="text" name="id" id="id" placeholder="Identifiant" required/>
					<input type="password" name="mdp" id="mdp" placeholder="Mot de passe" required/></br>
					<input type="submit" name="send" class="button" value="Envoi login" />
				</form>
				<script>function hd(){ $(\'#modal\').hide();}</script>
				<script>function home(){ document.location.href="index.php";}</script>
				<div  id="modal" >
										<h1>Informations !</h1>
										<div id="dialog1" ></div>
										<a class="no" onclick="hd();home();">OK</a>
				</div>
			<article >
	<script>
	$("#modal").hide();
	//Initialize the tooltips
	$("#login :input").tooltipster({
				         trigger: "custom",
				         onlyOne: false,
				         position: "bottom",
				         multiple:true,
				         autoClose:false});
		jQuery.validator.addMethod(
			  "regex",
			   function(value, element, regexp) {
			       if (regexp.constructor != RegExp)
			          regexp = new RegExp(regexp);
			       else if (regexp.global)
			          regexp.lastIndex = 0;
			          return this.optional(element) || regexp.test(value);
			   },"erreur champs non valide"
			);	
	$("#login").submit(function( e ){
        e.preventDefault();
		$("#modal").hide();
						
		var $url="ajax/valide_connect.php";
		if($("#login").valid())
		{		
			var formData = {
			"id" 					: $("#id").val(),
   			"mdp"					: $("#mdp").val()								   		
			};	
							
			var filterDataRequest = $.ajax(
    		{
												
        		type: "POST", 
        		url: $url,
        		dataType: "json",
				encode          : true,
        		data: formData,	

			});
				
			filterDataRequest.done(function(data)
			{
				if ( ! data.success)
				{		
						var $msg="erreur-></br><ul style=\"list-style-type :decimal;padding:0 5%;\">";
						if (data.errors.message) {
							$x=data.errors.message;
							$msg+="<li>";
							$msg+=$x;
							$msg+="</li>";
							}
						if (data.errors.requete) {
							$x=data.errors.requete;
							$msg+="<li>";
							$msg+=$x;
							$msg+="</li>";
							}
						
						$msg+="</ul>";
				}
				else
				{
						$msg="";
						if(data.message){$msg+="</br>";$x=data.message;$msg+=$x;}
				}
				
					$("#dialog1").html($msg);$("#modal").show();

				});
			filterDataRequest.fail(function(jqXHR, textStatus)
			{
				
     			if (jqXHR.status === 0){alert("Not connect.n Verify Network.");}
    			else if (jqXHR.status == 404){alert("Requested page not found. [404]");}
				else if (jqXHR.status == 500){alert("Internal Server Error [500].");}
				else if (textStatus === "parsererror"){alert("Requested JSON parse failed.");}
				else if (textStatus === "timeout"){alert("Time out error.");}
				else if (textStatus === "abort"){alert("Ajax request aborted.");}
				else{alert("Uncaught Error.n" + jqXHR.responseText);}
			});
		}
	});
   
	$("#login").validate({
		rules:
		{
													
			"id": {required: true},
			"mdp": {required: true}
		},
		messages:
		{
        	"id":
          	{
            	required: "Vous devez saisir un identifiant valide"
          	},
			"mdp":
          	{
            	required: "Vous devez saisir un mot de passe valide"
          	}
		},
		errorPlacement: function (error, element) {
			$(element).tooltipster("update", $(error).text());
			$(element).tooltipster("show");
		},
		success: function (label, element)
		{
			$(element).tooltipster("hide");
		}
   	});
	</script>					
		
		';
	
	}
	
	public function genererMDP ($longueur = 8){
		// initialiser la variable $mdp
		$mdp = "";
	
		// Définir tout les caractères possibles dans le mot de passe,
		// Il est possible de rajouter des voyelles ou bien des caractères spéciaux
		$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ&#@$*!";
	
		// obtenir le nombre de caractères dans la chaîne précédente
		// cette valeur sera utilisé plus tard
		$longueurMax = strlen($possible);
	
		if ($longueur > $longueurMax) {
			$longueur = $longueurMax;
		}
	
		// initialiser le compteur
		$i = 0;
	
		// ajouter un caractère aléatoire à $mdp jusqu'à ce que $longueur soit atteint
		while ($i < $longueur) {
			// prendre un caractère aléatoire
			$caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);
	
			// vérifier si le caractère est déjà utilisé dans $mdp
			if (!strstr($mdp, $caractere)) {
				// Si non, ajouter le caractère à $mdp et augmenter le compteur
				$mdp .= $caractere;
				$i++;
			}
		}
	
		// retourner le résultat final
		return $mdp;
	}
	
	//Affiche l'équipe de Kaliémie
	public function returnPageEquipe(){
		return '
				<div class="equipe_apercu">
					<div class="content_equipe">
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar2.jpg">
							<div class="lib">
								<p>
									Alexandre Nossocip<br>
									Administrateur du site
								</p>
							</div>
						</div>
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar1.jpg">
								<div class="lib">
									<p>
										Ninet Lanctot<br>
										Infirmière
									</p>
								</div>
						</div>
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar3.jpg">
								<div class="lib">
									<p>
										Julie Grondin<br>
										Infirmière
									</p>
								</div>
						</div>
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar4.jpg">
								<div class="lib">
									<p>
										Laure Piedalue<br>
										Infirmière
									</p>
								</div>
						</div>
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar5.jpg">
								<div class="lib">
									<p>
										Romaine Talon<br>
										Infirmière
									</p>
								</div>
						</div>
					</div>
					<div class="content_equipe">
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar6.jpg">
								<div class="lib">
									<p>
										Elodie Couet<br>
										Infirmière
									</p>
								</div>
						</div>
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar7.jpg">
								<div class="lib">
									<p>
										Ambra Corbeil<br>
										Infirmière
									</p>
								</div>
						</div>
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar8.jpg">
								<div class="lib">
									<p>
										Rive Arpin<br>
										Infirmière
									</p>
								</div>
						</div>
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar9.jpg">
								<div class="lib">
									<p>
										Simone Coulomb<br>
										Infirmière
									</p>
								</div>
						</div>
					</div>
				</div>
				';
	}
	
	//Menu des options de l'administrateur
	public function optionAdmin(){
		return '
				
					<div class="optionAdmin"><br><br>
						<ul>
							<li>
								<a href="#">Modifier mes informations</a>
							</li>
							<li>
								<a href="gestPatient.php">Gestion des patients</a>
							</li>
							<li>
								<a href="gestInfirmiere.php">Gestion des infirmières</a>
							</li>
							<li>
								<a href="gestPersonneC.php">Gestion des personnes de confiance</a>
							</li>
							<li>
								<a href="#">Ajouter du contenu au site</a>
							</li>
							<li>
								<a href="#">Gestion des commentaires</a>
							</li>
							<li>
								<a href="#">Validation des témoignages</a>
							</li>
							<li>
								<a href="#">Création de rendez-vous</a>
							</li>
					</div>
				
				';
	}
	
	public function optionPatient(){
		return '
					<div class="optionadmin"><br><br>
						<ul>
							<li>
								<a href="#">Modifier mes informations</a>
							</li>
							<li>
								<a href="#">Gestion de la personne de confiance</a>
							</li>
							<li>
								<a href="#">Ajouter un commentaire</a>
							</li>
							<li>
								<a href="ajouterTemoignageP.php">Ajouter un témoignage</a>
							</li>
					</div>
				';
	}
	
	// Affiche les informations en top de l'accueil
	public function AfficheInfosAcc(){
		return '
				<div class="top-acc">
    				<div class="col-xs-2">
    					<h1>
							<img class="img-responsive" id="logo" src="./images/logo.png" alt="logo"/>
							
						</h1>
					</div>
					<div class="col-xs-4">
						<h1>
							Kaliémie
						</h1>
					</div>
    				<div class="col-xs-6">
						<p>
							Bienvenue sur le site de Kaliémie.<br>
							Ici sont présentés les actualités et les témoignages des patients sur leurs rendez-vous et 
							la qualité de ceux-ci.<br>
							Sur le site vous sera aussi présenté toutes les personnes faisant parties de notre équipe.
						</p>
 					</div>
				</div>
				';
	}
	
	//Affiche les mentions légales du site
	public function mentionsLegales(){
		return '
				<br><br><br><br>
					Société ALMAPACASA - Groupe de travail en BTS Services Informatiques aux Organisations<br>
					Propriétaires : PICOSSON Alexandre - GIRAUDEAU Samantha - HERBERT Calvin - MALMEJEAN Paulin - MOREAU Maxime<br>
					Adresse : 115 Boulevard du Massacre, 44100 Nantes, France<br>
					Adresse de contact : almapacasa@yopmail.com<br>
					Hébergement du site : <br>
				
				';
	}
	
	//Affiche le formulaire d'ajout de témoignage
	public function nouveauFormulaireTemoignages(){
		return '<br><br><br>
				<form action="formTemoignage.php" method="post">
					<p>
						<p>Prénom : '.$_SESSION['prenom'].'<br></p>
					    <p>Nom : '.$_SESSION['nom'].'<br></p>
						<p>Date de votre consultation : <input type="text" name="dateConsult" /><br></p>
						<p>Votre consultation : <textarea type="text" name="temoignagePatient" ></textarea><br></p>
					    <input type="submit" value="Valider" />
					</p>
				</form>
				';
	}
	
	//Affiche les options de modification d'un patient
	public function optionsModifsPatients(){
		return '
				<div class="col-md-5">
					<div><br><br><br><br><br><br>
						<ul>
							<li>
								<a href="ajoutPatientA.php">Ajouter un patient</a>
							</li>
							<li>
								<a href="modifPatientA.php">Modifier un patient</a>
							</li>
							<li>
								<a href="deletePatientA.php">Supprimer un patient</a>
							</li>
							
					</div>
				</div>';
	}
	
	//Affiche les options de modification d'une infirmiere
	public function optionsModifsInfirmiere(){
		return '
				<div class="col-md-5">
					<div><br><br><br><br><br><br>
						<ul>
							<li>
								<a href="ajoutInfirmiereA.php">Ajouter une infirmiere</a>
							</li>
							<li>
								<a href="modifInfirmiereA.php">Modifier une infirmiere</a>
							</li>
							<li>
								<a href="deleteInfirmiereA.php">Supprimer une infirmiere</a>
							</li>
				
					</div>
				</div>';
	}
	
	//Affiche les options de modification d'une infirmiere
	public function optionsModifsPersonneC(){
		return '
				<div class="col-md-5">
					<div><br><br><br><br><br><br>
						<ul>
							<li>
								<a href="ajoutPersonneCA.php">Ajouter une personne de confiance</a>
							</li>
							<li>
								<a href="modifPersonneCA.php">Modifier une personne de confiance</a>
							</li>
							<li>
								<a href="deletePersonneCA.php">Supprimer une personne de confiance</a>
							</li>
	
					</div>
				</div>';
	}
	
	//Formulaire d'ajout d'un nouveau patient
	public function formAjoutPatient(){
		return '<br><br><br>
				<div class="formPatient">
					<form action="enregistrement/formAddPatient.php" method="post">
						<p>
							<h1>Ajout d\' un nouveau patient: </h1>
							<label>Id : </label><input type="text" name="id" /><br>
							<label>Id (personne de confiance) : </label><input type="text" name="id_her" /><br>
							<label>Prénom : </label><input type="text" name="prenom" /><br>
						    <label>Nom : </label><input type="text" name="nom" /><br>
							<label>Identifiant : </label><input type="text" name="login" /><br>
							<label>Mot de passe : </label><input type="text" name="mdp" /><br>
							<label>Date de naissance : </label><input type="date" name="dateNaiss"><br>
							<label>Sexe : </label><input type="text" placeholder="M/F" name="sexe" /><br>
							<label>Rue : </label><input type="text" name="rue" /><br>
							<label>Code postal : </label><input type="text" name="cp" /><br>
							<label>Ville : </label><input type="text" name="ville" /><br>
							<label>Téléphone : </label><input type="text" placeholder="06.00.01.02.03" name="tel" /><br>
							<label><input type="hidden" name="droit" value="2"/>
						    <input type="submit" value="Valider" />
						</p>
					</form>
				</div>
				';
	}
	
	//Formulaire d'ajout d'une nouvelle infirmiere
	public function formAjoutInfirmiere(){
		return '<br><br><br>
				<div class="formPatient">
					<form action="enregistrement/formAddInfirmiere.php" method="post">
						<p>
							<h1>Ajout d\' une nouvelle infirmière: </h1>
							<label>Id : </label><input type="text" name="id" /><br>
							<label>Url Photo : </label><input type="text" name="urlphoto" /><br>
							<label>Prénom : </label><input type="text" name="prenom" /><br>
						    <label>Nom : </label><input type="text" name="nom" /><br>
							<label>Identifiant : </label><input type="text" name="login" /><br>
							<label>Mot de passe : </label><input type="text" name="mdp" /><br>
							<label>Date de naissance : </label><input type="date" name="dateNaiss"><br>
							<label>Sexe : </label><input type="text" placeholder="M/F" name="sexe" /><br>
							<label>Rue : </label><input type="text" name="rue" /><br>
							<label>Code postal : </label><input type="text" name="cp" /><br>
							<label>Ville : </label><input type="text" name="ville" /><br>
							<label>Téléphone : </label><input type="text" placeholder="06.00.01.02.03" name="tel" /><br>
							<label><input type="hidden" name="droit" value="1"/>
						    <input type="submit" value="Valider" />
						</p>
					</form>
				</div>
				';
	}
	
	//Formulaire d'ajout d'une nouvelle personne de confiance
	public function formAjoutPersonneC(){
		return '<br><br><br>
				<div class="formPatient">
					<form action="enregistrement/formAddPersonneC.php" method="post">
						<p>
							<h1>Ajout d\' une personne de confiance: </h1>
							<label>Id : </label><input type="text" name="id" /><br>
							<label>Prénom : </label><input type="text" name="prenom" /><br>
						    <label>Nom : </label><input type="text" name="nom" /><br>
							<label>Identifiant : </label><input type="text" name="login" /><br>
							<label>Mot de passe : </label><input type="text" name="mdp" /><br>
							<label>Date de naissance : </label><input type="date" name="dateNaiss"><br>
							<label>Sexe : </label><input type="text" placeholder="M/F" name="sexe" /><br>
							<label>Rue : </label><input type="text" name="rue" /><br>
							<label>Code postal : </label><input type="text" name="cp" /><br>
							<label>Ville : </label><input type="text" name="ville" /><br>
							<label>Téléphone : </label><input type="text" placeholder="06.00.01.02.03" name="tel" /><br>
							<label><input type="hidden" name="droit" value="0"/>
						    <input type="submit" value="Valider" />
						</p>
					</form>
				</div>
				';
	}
	
	public function afficheValidAdd(){
		return '<br><br><br><br>
				Votre action est bien été effectué.
				';
	}
	
	public function afficheErreurAdd(){
		return '<br><br><br><br>
				Votre action n\'a pas fonctionnée, merci de bien vouloir réessayer.
				';
	}
	

	//Modification des informations personnelles
	public function modifMesInformations(){
		return '<br><br><br>
				<form action="formModifInfoPerso.php" method="post">
				<p>
					<label>Prénom : </label><input type="text" name="prenom" /><br>
				   	<label>Nom : </label><input type="text" name="nom" /><br>
					<label>Identifiant : </label><input type="text" name="login" /><br>
					<label>Mot de passe : </label><input type="text" name="mdp" /><br>
					<label>Date de naissance : </label><input type="date" name="dateNaiss"><br>
					<label>Sexe : </label><input type="text" name="sexe" /><br>
					<label>Rue : </label><input type="text" name="rue" /><br>
					<label>Code postal : </label><input type="text" name="cp" /><br>
					<label>Ville : </label><input type="text" name="ville" /><br>
					<label>Téléphone : </label><input type="text" name="tel" /><br>
				    <input type="submit" value="Valider" />
				</p>
				</form>';
	}
	
	//Formulaire de modification des informations des patients
	public function formModifPatient(){
		return '
				
				';	
		}
	}
	?>

