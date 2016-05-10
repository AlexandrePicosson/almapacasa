<?php
class controleur {
	
	private $mypdo;
	private $db;
	public function __construct() {
		$this->mypdo = new mypdo();
		$this->db = $this->mypdo->conexion;
	}
	
	public function __get($propriete) {
		switch ($propriete) {
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
	
	
	//Affiche le formulaire de connexion
	public function retourne_formulaire_login() {
		return '
<article>
    <script>$(document).ready(function(){$("#maModale1").modal();});</script>
    <div class="modal fade" id="maModale1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding: 35px 50px;">
                    <button type="button" class="close" data-dismiss="modal" onclick="hd1();home();">&times;</button>
                    <h4><span class="glyphicon glyphicon-lock"></span> Login</h4>
                </div>
                <div class="modal-body" style="padding: 40px 50px;">
                    <form id="login" method="post" class="login">
                        <div class="form-group">
                            <label for="username"><span class="glyphicon glyphicon-user"></span> Utilisateur</label>
                            <input type="text" name="id" id="username" placeholder="Identifiant" required>
                        </div>
                        <div class="form-group">
                            <label for="password"><span class="glyphicon glyphicon-eye-open"></span> Mot de passe</label>
                            <input type="password" name="mdp" id="password" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="maModale2" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding: 35px 50px;">
                    <button type="button" class="close" data-dismiss="modal" onclick="hd1();home();">&times;</button>
                    <h4>Connexion</h4>
                    <div id="dialog1"></div>
                    <button type="submit" class="btn btn-danger btn-default pull-left" onclick="hd2();" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
                    
                </div>
            </div>
        </div>
    </div>
    
    <script>function hd1(){$("#maModale1").hide();}</script>
    <script>function hd2(){$("#maModale2").hide();}</script>
    <script>function home(){document.location.href="index.php";}</script>
</article>

<script>
    $("#modal").hide();
    //Tootipster part
    $("#login :input").tooltipster({
        trigger:"custom", 
        onlyOne:false,
        position:"bottom",
        multiple:true,
        autoClose:false});
    jQuery.validator.addMethod("regex", function(value, element, regexp) {
                if (regexp.constructor != RegExp)
                    regexp = new RegExp(regexp);
                else if (regexp.global)
                    regexp.lastIndex = 0;
                return this.optional(element) || regexp.test(value);
            },"erreur champs non valide"
    );
    
    $("#login").submit(function(e){
        e.preventDefault();
        $("#modal").hide();
        var $url = "ajax/valide_connect.php";
        if($("#login").valid())
        {
            var formData = { 
                "id" : $("#username").val(),
                "mdp" : $("#password").val()
            };
            var filterDataRequest = $.ajax(
                    {
                        type: "POST",
                        url: $url,
                        dataType: "json",
                        encode : true,
                        data: formData
                    }
            );
            filterDataRequest.done(
                    function(data){
                        if(! data.success)
                        {
                            var $msg="Erreur : </br><ul style=&#34;list-style-type : decimal; padding:0 5%;&#34;>";
                            if(data.errors.message) {
                                $x=data.errors.message;
                                $msg+="<li>";
                                $msg+=$x;
                                $msg+="</li>";
                            }
                            if(data.errors.requete)
                            {
                                $x=data.errors.requete;
                                $msg+="<li>";
                                $msg+=$x;
                                $msg+="</li>";
                            }
                            $msg+="</ul>";
                            $("#dialog1").html($msg);
                        	$("#maModale2").modal({backdrop:true});
                        }
                        else
                        {
                            home();
                        }
                        
                    }
            );
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
        rules :
        {
            "username": {required: true},
            "password": {required: true}
        },
        messages:
        {
            "username":
            {
                required : "Vous devez saisir un identifiant"
            },
            "password":
            {
                required : "Vous devez saisir un mot de passe"
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
				
				<div class="space"></div>
				<center>
					<div class="col-md-3">
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar2.jpg">
							<div class="lib">
								<p>
									Alexandre Nossocip<br>
									Administrateur du site
								</p>
							</div>
						</div>
					</div>
				</center>
				<center>
					<div class="col-md-3">
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar1.jpg">
								<div class="lib">
									<p>
										Ninet Lanctot<br>
										Infirmière
									</p>
								</div>
						</div>
					</div>
				</center>
				<center>
					<div class="col-md-3">
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar3.jpg">
								<div class="lib">
									<p>
										Julie Grondin<br>
										Infirmière
									</p>
								</div>
						</div>
					</div>
				</center>
				<center>
					<div class="col-md-3">
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar4.jpg">
								<div class="lib">
									<p>
										Laure Piedalue<br>
										Infirmière
									</p>
								</div>
						</div>
					</div>
				</center>
				<center>
					<div class="col-md-3">
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
				</center>
				<center>
					<div class="col-md-3">
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar6.jpg">
								<div class="lib">
									<p>
										Elodie Couet<br>
										Infirmière
									</p>
								</div>
						</div>
					</div>
				</center>
				<center>
					<div class="col-md-3">
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar7.jpg">
								<div class="lib">
									<p>
										Ambra Corbeil<br>
										Infirmière
									</p>
								</div>
						</div>
					</div>
				</center>
				<center>
					<div class="col-md-3">	
						<div class="apercu_equipe">
							<img src="../almapacasa/images/avatar8.jpg">
								<div class="lib">
									<p>
										Rive Arpin<br>
										Infirmière
									</p>
								</div>
						</div>
					</div>
				</center>
				';
	}
	
	//Menu des options de l'administrateur
	public function optionAdmin(){
		return '
				
					<div class="optionAdmin">
						<ul>
							<li>
								<a href="modifsInfosAdmin.php">Modifier mes informations</a>
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
								<a href="upCommentaire.php">Validation des commentaires</a>
							</li>
							<li>
								<a href="upTemoignage.php">Validation des témoignages</a>
							</li>
							<li>
								<a href="gestRDVA.php">Gestion des rendez-vous</a>
							</li>
					</div>
				
				';
	}
	
	//Affiche les témoignages
	public function afficheTemoignage(){
		
				$tem = $this->mypdo->afficheTemoignageDB();
				$return = '<h1><u>Témoignages récents :</u></h1>';
				if($tem && $tem != null)
				{
				while ($var = $tem->fetch(PDO::FETCH_ASSOC)){
					$return = $return.'<div id="lesTemoignages"><li value = "'.$var['id'].'"><label><u>Identifiant du patient :</u></label>'.$var['idPatient']."<br><label><u>Son témoignage :</u></label>".$var['libelle'].'</li><br></div>';
				}
				
		}
		return $return;
	}
	
	//SELECT des temoignages non validées
	public function selectTem(){
		$tab = $this->mypdo->selectTem();
		$return = '<form id="selectTemoignage" method ="post"><label>Veuillez choisir le témoignage à ajouter :</label><br><select name="id" id="id">';
		if($tab && $tab != null)
		{
			while ($var = $tab->fetch(PDO::FETCH_ASSOC)){
				$return = $return.'<option value = "'.$var['id'].'">'.$var['id'].'</option>';
			}
			$return = $return.'</select>
					<input id="submit" type="submit" name="send" class="button" value="Valider" />
					</form>
					';
		}
		return $return;
	}
	
	//SELECT des commentaires non validées
	public function selectCom(){
		$tab = $this->mypdo->selectCom();
		$return = '<form id="selectCommentaire" method ="post"><label>Veuillez choisir le commentaire à valider :</label><br><select name="id" id="id">';
		if($tab && $tab != null)
		{
			while ($var = $tab->fetch(PDO::FETCH_ASSOC)){
				$return = $return.'<option value = "'.$var['id'].'">'.$var['id'].'</option>';
			}
			$return = $return.'</select>
					<input id="submit" type="submit" name="send" class="button" value="Valider" />
					</form>
					';
		}
		return $return;
	}
	
	//Menu des options de la personne de confiance
	public function optionPersonneC(){
		return '
	
					<div class="optionAdmin">
						<ul>
							<li>
								<a href="modifsInfosPersonneC.php">Modifier mes informations</a>
							</li>
					</div>
	
				';
	}
	
	
	//Menu des options du patient
	public function optionPatient(){
		return '
					<div class="optionAdmin">
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
    					<img class="img-responsive" id="logo" src="./images/logo.png" alt="logo"/>
					</div>
					
    				<div class="col-xs-10">
						<h3>
							Bienvenue sur le site de Kaliémie.<br>
							Ici sont présentés les actualités et les témoignages des patients sur leurs rendez-vous et 
							la qualité de ceux-ci.<br>
							Sur le site vous sera aussi présenté toutes les personnes faisant parties de notre équipe.
						</h3>
 					</div>
				</div>
				';
	}
	
	//Affiche les mentions légales du site
	public function mentionsLegales(){
		return '
				<br>
					Société ALMAPACASA - Groupe de travail en BTS Services Informatiques aux Organisations<br>
					Propriétaires : PICOSSON Alexandre - GIRAUDEAU Samantha - HERBERT Calvin - MALMEJEAN Paulin - MOREAU Maxime<br>
					Adresse : 115 Boulevard du Massacre, 44100 Nantes, France<br>
					Adresse de contact : almapacasa@yopmail.com<br>
					Hébergement du site : <br>
					<br><br><br><br><br><br><br>CNNNNNNNIIIIIIIILLLLLLLLLLLLL
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
	
	//Affiche les options de "Gestion des patients"
	public function optionsModifsPatients(){
		return '
				<div class="col-md-5">
					<div class="optPatient">
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
	
	//Affiche les options de "Gestion des infirmieres"
	public function optionsModifsInfirmiere(){
		return '
				<div class="col-md-5">
					<div class="optInfirmiere">
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
	
	//Affiche les options de "Gestion des personnes de confiance"
	public function optionsModifsPersonneC(){
		return '
				<div class="col-md-5">
					<div class="optPersonnesC">
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
						</ul>
					</div>
				</div>';
	}
	
	//Modification des informations d'un patient
	public function formModifPatient(){
		$tab = $this->mypdo->modifPatientRecupDB();
		$return = '<form id="selectPatient" method ="post"><label>Veuillez choisir le patient à modifier : </label><br><select name="id" id="id">';
		if($tab && $tab != null)
		{
			while ($var = $tab->fetch(PDO::FETCH_ASSOC)){
				$return = $return.'<option value = "'.$var['id'].'">'.$var['nom']." ".$var['prenom'].'</option>';
			}
			$return = $return.'</select>
					<input id="submit" type="submit" name="send" class="button" value="Valider" />
					</form>
					';
		}
		return $return;
	}
	
	//Affiche la liste des infirmieres
	public function formModifInfirmiere(){
		$tab = $this->mypdo->modifInfirmiereRecupDB();
		$return = '<form id="selectInfirmiere" method ="post"><label>Veuillez choisir l\'infirmiere à modifier : </label><br><select name="id" id="id">';
		if($tab && $tab != null)
		{
			while ($var = $tab->fetch(PDO::FETCH_ASSOC)){
				$return = $return.'<option value = "'.$var['id'].'">'.$var['nom']." ".$var['prenom'].'</option>';
			}
			$return = $return.'</select>
					<input id="submit" type="submit" name="send" class="button" value="Valider" />
					</form>
					';
		}
		return $return;
	}
	
	//Affiche la liste des personnes de confiance
	public function formModifPersonneC(){
		$tab = $this->mypdo->modifPersonneCRecupDB();
		$return = '<form id="selectPersonneC" method ="post"><label>Veuillez choisir la personne de confiance à modifier : </label><br><select name="id" id="id">';
		if($tab && $tab != null)
		{
			while ($var = $tab->fetch(PDO::FETCH_ASSOC)){
				$return = $return.'<option value = "'.$var['id'].'">'.$var['nom']." ".$var['prenom'].'</option>';
			}
			$return = $return.'</select>
					<input id="submit" type="submit" name="send" class="button" value="Valider" />
					</form>
					';
		}
		return $return;
	}
	
	
	//Retourne les formulaires d'ajout/modif/suppression des patients
	public function retourne_formulaire_patient($type, $id = ''){
		$nom = '';
		$prenom = '';
		$login = '';
		$mdp = '';
		$dateNaiss = '';
		$sexe='';
		$rue='';
		$cp='';
		$ville='';
		$telephone='';
		$titreForm='';
		$lblBouton = '';
		$radioMchecked = '';
		$radioFchecked = '';
		
		if($type == 'ajout')
		{
			$titreForm = "Ajout d'un patient :";
			$lblBouton = "Ajouter";
		}
		
		if($type == 'modif')
		{
			$titreForm = "Modification d'un patient :";
			$lblBouton = "Modifier";
		}
		
		if($type == 'suppr')
		{
			$titreForm = "Suppression d'un patient :";
			$lblBouton = "Supprimer";
		}
		
		if($type == 'suppr' || $type == 'modif')
		{
			
			$result = $this->mypdo->trouvePatient($id);
			
			if($result != null){
				$nom = $result['nom'];
				$prenom = $result['prenom'];
				$login = $result['login'];
				$mdp = $result['mdp'];
				$dateNaiss = $result['anNaiss'];
				$sexe=$result['sexe'];
				if($sexe == 'M')
				{
					$radioMchecked = 'checked';
					$radioFchecked = '';
				}else{
					$radioMchecked = '';
					$radioFchecked = 'checked';
				}
				$rue=$result['rue'];
				$cp=$result['cp'];
				$ville=$result['ville'];
				$telephone=$result['telephone'];
			}
		}
		
		$form = ' <form class="formulairePatient" id="formPatient" method ="post"><article> <h3><u>'.$titreForm.'</u></h3>';
		
		if($type == 'ajout')
		{
			$form = $form.'<br><div> 
								<label>Identifiant :</label><input type="text" name="login" id="login" value="'.$login.'" required /> </br>
								<label>Mot de Passe :</label><input type="password" name="mdp" id="mdp" value="" required /></br>
						   </div>';
		}else{
			$form = $form.'<div style="display : none;">
								Identifiant : <input type="text" name="login" id="login" value="'.$login.'" required /> </br>
								Mot de Passe : <input type="password" name="mdp" id="mdp" value="123" required /></br>
						   </div>';
		}
		
		$form = $form.'
					</br><h4><u>Patient</u></h4>
					<label>Nom : </label><input type="text" name="nom" id="nom" placeholder="votre nom" value="'.$nom.'" required /><br>
					<label>Prénom : </label><input type="text" name="prenom" id="prenom" placeholder="votre prenom" value="'.$prenom.'" required /></br>
					<label>Date de naissance :</label><input type="date" name="annaiss" id="annaiss" value="'.$dateNaiss.'" required /></br>
					<label>Sexe :</label>
					<input type="radio" name="sexe" id="Masculin" value="Masculin"' .$radioMchecked.' required /> Homme
					<input type="radio" name="sexe" id="Feminin" value="Feminin"' .$radioFchecked.' required /> Femme</br>
					<label>Adresse :</label><input type="text" name="rue" id="rue" placeholder="Nom de rue" value="'.$rue.'" required />
					<input type="text" name="cp" id="cp" placeholder="Code postal" value="'.$cp.'" required />
					<input type="text" name="ville" id="ville" placeholder="Ville" value="'.$ville.'" required /></br>
					<label>Téléphone : </label><input type="text" name="telephone" id="telephone" placeholder="06.01.02.03.04" value="'.$telephone.'" required /><br><br>
					<input id="submit1" type="submit" onclick="" name="send" class="button" value="' . $lblBouton . '" />
					</form>
					<script>function hd(){ $(\'#modal\').hide();}</script>
					<script>function reload(){window.location.reload();}</script>
					<div id="modal">
							<form id="formModale">
							<h1>Informations !</h1>
							<div id="dialog"></div>
							<input type="text" name="id" value="" style="display:none;"/>
							<input type="submit" value="Ok"/>
							</form>
					</div>
					</article>
					<script>
						$(\'#modal\').hide();
						$("#formPatient :input").tooltipster({
													trigger:"custom",
													onlyOne: false,
													position:"bottom",
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
						
						$(\'#formPatient\').submit(function(e){
							
							e.preventDefault();
							$(\'#modal\').hide();
							var $url ="ajax/valide_ajout_patient.php";
							if($(\'#submit1\').prop("value")=="Modifier"){$url="ajax/valide_modif_patient.php";}
							if($(\'#submit1\').prop("value")=="Supprimer"){$url="ajax/valide_suppr_patient.php";}
							
							if($("#formPatient").valid())
							{
								var $sexe="M";
								if($("input[type=radio][name=sexe]:checked").attr("value")=="Feminin"){$sexe = "F";}
								var $mdp = "";
								if($("#submit1").prop("value")=="Ajouter"){ $mdp = $("#mdp").val(); };
							
								var formData = {
									"login" : $("#login").val(),
									"mdp" : $mdp,
									"nom" : $("#nom").val(),
									"prenom" : $("#prenom").val(),
									"anNaiss" : $("#annaiss").val(),
									"sexe" : $sexe,
									"rue" : $("#rue").val(),
									"cp" : $("#cp").val(),
									"ville" : $("#ville").val(),
									"telephone" : $("#telephone").val()
								};
							
								
								var filterDataRequest = $.ajax(
								{
									type: "POST",
        							url: $url,
        							dataType: "json",
									encode : true,
        							data: formData
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
											if(data.message){$msg;$x=data.message;$msg+=$x;}
									}
						
										$("#dialog").html($msg);$("#modal").show();
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
							$("#formPatient").validate({
								rules:
								{
													
									"nom": {required: true},
									"prenom": {required: true},
									"rue": {required: true},
									"telephone": {required: true},
									"cp":{required: true,regex:/^\d{5}$/},
									"ville": {required: true},
									"rue": {required: true},
									"annaiss":{required: true},
									"login": {required :true},
									"mdp": {required : true}
								},
								messages:
								{
						        	"nom":
						          	{
						            	required: "Vous devez saisir un nom valide"
						          	},
									"prenom":
						          	{
						            	required: "Vous devez saisir un prenom valide"
						          	},
									"rue":
									{
						            	required: "Vous devez saisir une adresse valide"
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
		return $form;
		
	}
	
	
	//Retourne les formulaires d'ajout/modif/suppression des infirmieres
	public function retourne_formulaire_infirmiere($type, $id = ''){
		$nom = '';
		$prenom = '';
		$urlphoto = '';
		$login = '';
		$mdp = '';
		$dateNaiss = '';
		$sexe='';
		$rue='';
		$cp='';
		$ville='';
		$telephone='';
		$titreForm='';
		$lblBouton = '';
		$radioMchecked = '';
		$radioFchecked = '';
	
		if($type == 'ajout')
		{
			$titreForm = "Ajout d'une infirmiere :";
			$lblBouton = "Ajouter";
		}
	
		if($type == 'modif')
		{
			$titreForm = "Modification d'une infirmiere :";
			$lblBouton = "Modifier";
		}
	
		if($type == 'suppr')
		{
			$titreForm = "Suppression d'une infirmiere :";
			$lblBouton = "Supprimer";
		}
	
		if($type == 'suppr' || $type == 'modif')
		{
				
			$result = $this->mypdo->trouveInfirmiere($id);
				
			if($result != null){
				$nom = $result['nom'];
				$prenom = $result['prenom'];
				$urlphoto = $result['urlPhoto'];
				$login = $result['login'];
				$mdp = $result['mdp'];
				$dateNaiss = $result['anNaiss'];
				$sexe=$result['sexe'];
				if($sexe == 'M')
				{
					$radioMchecked = 'checked';
					$radioFchecked = '';
				}else{
					$radioMchecked = '';
					$radioFchecked = 'checked';
				}
				$rue=$result['rue'];
				$cp=$result['cp'];
				$ville=$result['ville'];
				$telephone=$result['telephone'];
			}
		}
	
		$form = ' <form class="formulaireInfirmiere" id="formInfirmiere" method ="post"><article> <h3><u>'.$titreForm.'</u></h3>';
	
		if($type == 'ajout')
		{
			$form = $form.'<br><div>
								<label>Identifiant :</label><input type="text" name="login" id="login" value="'.$login.'" required /> </br>
								<label>Mot de Passe :</label><input type="password" name="mdp" id="mdp" value="" required /></br>
						   </div>';
		}else{
			$form = $form.'<div style="display : none;">
								Identifiant : <input type="text" name="login" id="login" value="'.$login.'" required /> </br>
								Mot de Passe : <input type="password" name="mdp" id="mdp" value="123" required /></br>
						   </div>';
		}
	
		$form = $form.'
					</br><h4><u>Infirmiere</u></h4>
					<label>Nom : </label><input type="text" name="nom" id="nom" placeholder="votre nom" value="'.$nom.'" required /><br>
					<label>Prénom : </label><input type="text" name="prenom" id="prenom" placeholder="votre prenom" value="'.$prenom.'" required /></br>
					<label>URL de la photo : </label><input type="text" name="urlphoto" id="urlphoto" placeholder="image.jpg" value="'.$urlphoto.'" required /></br>		
					<label>Date de naissance :</label><input type="date" name="annaiss" id="annaiss" value="'.$dateNaiss.'" required /></br>
					<label>Sexe :</label>
					<input type="radio" name="sexe" id="Masculin" value="Masculin"' .$radioMchecked.' required /> Homme
					<input type="radio" name="sexe" id="Feminin" value="Feminin"' .$radioFchecked.' required /> Femme</br>
					<label>Adresse :</label><input type="text" name="rue" id="rue" placeholder="Nom de rue" value="'.$rue.'" required />
					<input type="text" name="cp" id="cp" placeholder="Code postal" value="'.$cp.'" required />
					<input type="text" name="ville" id="ville" placeholder="Ville" value="'.$ville.'" required /></br>
					<label>Téléphone : </label><input type="text" name="telephone" id="telephone" placeholder="06.01.02.03.04" value="'.$telephone.'" required /><br><br>
					<input id="submit1" type="submit" onclick="" name="send" class="button" value="' . $lblBouton . '" />
					</form>
					<script>function hd(){ $(\'#modal\').hide();}</script>
					<script>function reload(){window.location.reload();}</script>
					<div id="modal">
							<form id="formModale">
							<h1>Informations !</h1>
							<div id="dialog"></div>
							<input type="text" name="id" value="" style="display:none;"/>
							<input type="submit" value="Ok"/>
							</form>
					</div>
					</article>
					<script>
						$(\'#modal\').hide();
						$("#formInfirmiere :input").tooltipster({
													trigger:"custom",
													onlyOne: false,
													position:"bottom",
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
	
						$(\'#formInfirmiere\').submit(function(e){
				
							e.preventDefault();
							$(\'#modal\').hide();
							var $url ="ajax/valide_ajout_infirmiere.php";
							if($(\'#submit1\').prop("value")=="Modifier"){$url="ajax/valide_modif_infirmiere.php";}
							if($(\'#submit1\').prop("value")=="Supprimer"){$url="ajax/valide_suppr_infirmiere.php";}
				
							if($("#formInfirmiere").valid())
							{
								var $sexe="M";
								if($("input[type=radio][name=sexe]:checked").attr("value")=="Feminin"){$sexe = "F";}
								var $mdp = "";
								if($("#submit1").prop("value")=="Ajouter"){ $mdp = $("#mdp").val(); };
				
								var formData = {
									"login" : $("#login").val(),
									"mdp" : $mdp,
									"urlphoto" : $("#urlphoto").val(),
									"nom" : $("#nom").val(),
									"prenom" : $("#prenom").val(),
									"anNaiss" : $("#annaiss").val(),
									"sexe" : $sexe,
									"rue" : $("#rue").val(),
									"cp" : $("#cp").val(),
									"ville" : $("#ville").val(),
									"telephone" : $("#telephone").val()
								};
				
	
								var filterDataRequest = $.ajax(
								{
									type: "POST",
        							url: $url,
        							dataType: "json",
									encode : true,
        							data: formData
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
											if(data.message){$msg;$x=data.message;$msg+=$x;}
									}
	
										$("#dialog").html($msg);$("#modal").show();
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
							$("#formInfirmiere").validate({
								rules:
								{
							
									"nom": {required: true},
									"prenom": {required: true},
									"urlphoto": {required: true},
									"rue": {required: true},
									"telephone": {required: true},
									"cp":{required: true,regex:/^\d{5}$/},
									"ville": {required: true},
									"rue": {required: true},
									"annaiss":{required: true},
									"login": {required :true},
									"mdp": {required : true}
								},
								messages:
								{
						        	"nom":
						          	{
						            	required: "Vous devez saisir un nom valide"
						          	},
									"prenom":
						          	{
						            	required: "Vous devez saisir un prenom valide"
						          	},
									"rue":
									{
						            	required: "Vous devez saisir une adresse valide"
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
		return $form;
	
	}
	
	//Retourne les formulaires d'ajout/modif/suppression des personnes de confiance
	public function retourne_formulaire_personneC($type, $id = ''){
		$nom = '';
		$prenom = '';
		$login = '';
		$mdp = '';
		$dateNaiss = '';
		$sexe='';
		$rue='';
		$cp='';
		$ville='';
		$telephone='';
		$titreForm='';
		$lblBouton = '';
		$radioMchecked = '';
		$radioFchecked = '';
	
		if($type == 'ajout')
		{
			$titreForm = "Ajout d'une personne de confiance :";
			$lblBouton = "Ajouter";
		}
	
		if($type == 'modif')
		{
			$titreForm = "Modification d'ue personne de confiance :";
			$lblBouton = "Modifier";
		}
	
		if($type == 'suppr')
		{
			$titreForm = "Suppression d'une personne de confiance :";
			$lblBouton = "Supprimer";
		}
	
		if($type == 'suppr' || $type == 'modif')
		{
				
			$result = $this->mypdo->trouvePersonneC($id);
				
			if($result != null){
				$nom = $result['nom'];
				$prenom = $result['prenom'];
				$login = $result['login'];
				$mdp = $result['mdp'];
				$dateNaiss = $result['anNaiss'];
				$sexe=$result['sexe'];
				if($sexe == 'M')
				{
					$radioMchecked = 'checked';
					$radioFchecked = '';
				}else{
					$radioMchecked = '';
					$radioFchecked = 'checked';
				}
				$rue=$result['rue'];
				$cp=$result['cp'];
				$ville=$result['ville'];
				$telephone=$result['telephone'];
			}
		}
	
		$form = ' <form class="formulairePersonneC" id="formPersonneC" method ="post"><article> <h3><u>'.$titreForm.'</u></h3>';
	
		if($type == 'ajout')
		{
			$form = $form.'<br><div>
								<label>Identifiant :</label><input type="text" name="login" id="login" value="'.$login.'" required /> </br>
								<label>Mot de Passe :</label><input type="password" name="mdp" id="mdp" value="" required /></br>
						   </div>';
		}else{
			$form = $form.'<div style="display : none;">
								Identifiant : <input type="text" name="login" id="login" value="'.$login.'" required /> </br>
								Mot de Passe : <input type="password" name="mdp" id="mdp" value="123" required /></br>
						   </div>';
		}
	
		$form = $form.'
					</br><h4><u>Personne de confiance</u></h4>
					<label>Nom : </label><input type="text" name="nom" id="nom" placeholder="votre nom" value="'.$nom.'" required /><br>
					<label>Prénom : </label><input type="text" name="prenom" id="prenom" placeholder="votre prenom" value="'.$prenom.'" required /></br>
					<label>Date de naissance :</label><input type="date" name="annaiss" id="annaiss" value="'.$dateNaiss.'" required /></br>
					<label>Sexe :</label>
					<input type="radio" name="sexe" id="Masculin" value="Masculin"' .$radioMchecked.' required /> Homme
					<input type="radio" name="sexe" id="Feminin" value="Feminin"' .$radioFchecked.' required /> Femme</br>
					<label>Adresse :</label><input type="text" name="rue" id="rue" placeholder="Nom de rue" value="'.$rue.'" required />
					<input type="text" name="cp" id="cp" placeholder="Code postal" value="'.$cp.'" required />
					<input type="text" name="ville" id="ville" placeholder="Ville" value="'.$ville.'" required /></br>
					<label>Téléphone : </label><input type="text" name="telephone" id="telephone" placeholder="06.01.02.03.04" value="'.$telephone.'" required /><br><br>
					<input id="submit1" type="submit" onclick="" name="send" class="button" value="' . $lblBouton . '" />
					</form>
					<script>function hd(){ $(\'#modal\').hide();}</script>
					<script>function reload(){window.location.reload();}</script>
					<div id="modal">
							<form id="formModale">
							<h1>Informations !</h1>
							<div id="dialog"></div>
							<input type="text" name="id" value="" style="display:none;"/>
							<input type="submit" value="Ok"/>
							</form>
					</div>
					</article>
					<script>
						$(\'#modal\').hide();
						$("#formPersonneC :input").tooltipster({
													trigger:"custom",
													onlyOne: false,
													position:"bottom",
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
	
						$(\'#formPersonneC\').submit(function(e){
				
							e.preventDefault();
							$(\'#modal\').hide();
							var $url ="ajax/valide_ajout_personneC.php";
							if($(\'#submit1\').prop("value")=="Modifier"){$url="ajax/valide_modif_personneC.php";}
							if($(\'#submit1\').prop("value")=="Supprimer"){$url="ajax/valide_suppr_personneC.php";}
				
							if($("#formPersonneC").valid())
							{
								var $sexe="M";
								if($("input[type=radio][name=sexe]:checked").attr("value")=="Feminin"){$sexe = "F";}
								var $mdp = "";
								if($("#submit1").prop("value")=="Ajouter"){ $mdp = $("#mdp").val(); };
				
								var formData = {
									"login" : $("#login").val(),
									"mdp" : $mdp,
									"nom" : $("#nom").val(),
									"prenom" : $("#prenom").val(),
									"anNaiss" : $("#annaiss").val(),
									"sexe" : $sexe,
									"rue" : $("#rue").val(),
									"cp" : $("#cp").val(),
									"ville" : $("#ville").val(),
									"telephone" : $("#telephone").val()
								};
				
	
								var filterDataRequest = $.ajax(
								{
									type: "POST",
        							url: $url,
        							dataType: "json",
									encode : true,
        							data: formData
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
	
										$("#dialog").html($msg);$("#modal").show();
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
							$("#formPersonneC").validate({
								rules:
								{
							
									"nom": {required: true},
									"prenom": {required: true},
									"rue": {required: true},
									"telephone": {required: true},
									"cp":{required: true,regex:/^\d{5}$/},
									"ville": {required: true},
									"rue": {required: true},
									"annaiss":{required: true},
									"login": {required :true},
									"mdp": {required : true}
								},
								messages:
								{
						        	"nom":
						          	{
						            	required: "Vous devez saisir un nom valide"
						          	},
									"prenom":
						          	{
						            	required: "Vous devez saisir un prenom valide"
						          	},
									"rue":
									{
						            	required: "Vous devez saisir une adresse valide"
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
		return $form;
	
	}
	
	//Retourne les informations personnelles d'un administrateur
	public function retourne_formulaire_modifsInfos_admin($type, $id = ''){
		$nom = '';
		$prenom = '';
		$login = '';
		$mdp = '';
		$dateNaiss = '';
		$sexe='';
		$rue='';
		$cp='';
		$ville='';
		$telephone='';
		$titreForm='';
		$lblBouton = '';
		$radioMchecked = '';
		$radioFchecked = '';
	
		
		if($type == 'modif')
		{
			$titreForm = "Modification de mes informations personnelles :";
			$lblBouton = "Modifier";
		}
	
		if($type == 'modif')
		{
		
			$result = $this->mypdo->trouveModifAdmin($id);
		
			if($result != null){
				$nom = $result['nom'];
				$prenom = $result['prenom'];
				$login = $result['login'];
				$mdp = $result['mdp'];
				$dateNaiss = $result['anNaiss'];
				$sexe=$result['sexe'];
				if($sexe == 'M')
				{
					$radioMchecked = 'checked';
					$radioFchecked = '';
				}else{
					$radioMchecked = '';
					$radioFchecked = 'checked';
				}
				$rue=$result['rue'];
				$cp=$result['cp'];
				$ville=$result['ville'];
				$telephone=$result['telephone'];
			}
		}
	
		$form = ' <form class="formulaireModifAdmin" id="formulaireModifAdmin" method ="post"><article> <h3><u>'.$titreForm.'</u></h3>';
	
		$form = $form.'
					</br><h4><u>Mes informations personnelles</u></h4>
					<label>Nom : </label><input type="text"  name="nom" id="nom" placeholder="votre nom" value="'.$nom.'" required /><br>
					<label>Prénom : </label><input type="text" style="background-color:darkgray;" readonly name="prenom" id="prenom" placeholder="votre prenom" value="'.$prenom.'" required /></br>
					<label>Date de naissance :</label><input type="date" style="background-color:darkgray;" readonly name="annaiss" id="annaiss" value="'.$dateNaiss.'" required /></br>
					<label>Sexe :</label>
					<input type="radio" name="sexe" id="Masculin" value="Masculin"' .$radioMchecked.' required /> Homme
					<input type="radio" name="sexe" id="Feminin" value="Feminin"' .$radioFchecked.' required /> Femme</br>
					<label>Adresse :</label><input type="text" name="rue" id="rue" placeholder="Nom de rue" value="'.$rue.'" required />
					<input type="text" name="cp" id="cp" placeholder="Code postal" value="'.$cp.'" required />
					<input type="text" name="ville" id="ville" placeholder="Ville" value="'.$ville.'" required /></br>
					<label>Téléphone : </label><input type="text" name="telephone" id="telephone" placeholder="06.01.02.03.04" value="'.$telephone.'" required /><br><br>
					<input id="submit1" type="submit" onclick="" name="send" class="button" value="' . $lblBouton . '" />
					</form>
					<script>function hd(){ $(\'#modal\').hide();}</script>
					<script>function reload(){window.location.reload();}</script>
					<div id="modal">
							<form id="formModale">
							<h1>Informations !</h1>
							<div id="dialog"></div>
							<input type="text" name="id" value="" style="display:none;"/>
							<input type="submit" value="Ok"/>
							</form>
					</div>
					</article>
					<script>
						$(\'#modal\').hide();
						$("#formulaireModifAdmin :input").tooltipster({
													trigger:"custom",
													onlyOne: false,
													position:"bottom",
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
	
						$(\'#formulaireModifAdmin\').submit(function(e){
	
							e.preventDefault();
							$(\'#modal\').hide();
							if($(\'#submit1\').prop("value")=="Modifier"){$url="ajax/valide_modifInfos_admin.php";}
	
							if($("#formulaireModifAdmin").valid())
							{
								var $sexe="M";
								if($("input[type=radio][name=sexe]:checked").attr("value")=="Feminin"){$sexe = "F";}
								var $mdp = "";
								
	
								var formData = {
									"login" : $("#login").val(),
									"mdp" : $mdp,
									"nom" : $("#nom").val(),
									"prenom" : $("#prenom").val(),
									"anNaiss" : $("#annaiss").val(),
									"sexe" : $sexe,
									"rue" : $("#rue").val(),
									"cp" : $("#cp").val(),
									"ville" : $("#ville").val(),
									"telephone" : $("#telephone").val()
								};
	
	
								var filterDataRequest = $.ajax(
								{
									type: "POST",
        							url: $url,
        							dataType: "json",
									encode : true,
        							data: formData
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
	
										$("#dialog").html($msg);$("#modal").show();
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
							$("#formulaireModifAdmin").validate({
								rules:
								{
				
									"nom": {required: true},
									"prenom": {required: true},
									"rue": {required: true},
									"telephone": {required: true},
									"cp":{required: true,regex:/^\d{5}$/},
									"ville": {required: true},
									"rue": {required: true},
									"annaiss":{required: true},
									"login": {required :true},
									"mdp": {required : true}
								},
								messages:
								{
						        	"nom":
						          	{
						            	required: "Vous devez saisir un nom valide"
						          	},
									"prenom":
						          	{
						            	required: "Vous devez saisir un prenom valide"
						          	},
									"rue":
									{
						            	required: "Vous devez saisir une adresse valide"
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
		return $form;
	
	}

	//Retourne les formulaires d'ajout/modif/suppression des personnes de confiance
	public function retourne_formulaire_modifsInfos_personneC($type, $id = ''){
		$nom = '';
		$prenom = '';
		$login = '';
		$mdp = '';
		$dateNaiss = '';
		$sexe='';
		$rue='';
		$cp='';
		$ville='';
		$telephone='';
		$titreForm='';
		$lblBouton = '';
		$radioMchecked = '';
		$radioFchecked = '';
	
	
		if($type == 'modif')
		{
			$titreForm = "Modification de mes informations personnelles :";
			$lblBouton = "Modifier";
		}
	
		if($type == 'modif')
		{
	
			$result = $this->mypdo->trouveModifPersonneC($id);
	
			if($result != null){
				$nom = $result['nom'];
				$prenom = $result['prenom'];
				$login = $result['login'];
				$mdp = $result['mdp'];
				$dateNaiss = $result['anNaiss'];
				$sexe=$result['sexe'];
				if($sexe == 'M')
				{
					$radioMchecked = 'checked';
					$radioFchecked = '';
				}else{
					$radioMchecked = '';
					$radioFchecked = 'checked';
				}
				$rue=$result['rue'];
				$cp=$result['cp'];
				$ville=$result['ville'];
				$telephone=$result['telephone'];
			}
		}
	
		$form = ' <form class="formulaireModifPersonneC" id="formulaireModifPersonneC" method ="post"><article> <h3><u>'.$titreForm.'</u></h3>';
	
		$form = $form.'
					</br><h4><u>Mes informations personnelles</u></h4>
					<label>Nom : </label><input type="text"  name="nom" id="nom" placeholder="votre nom" value="'.$nom.'" required /><br>
					<label>Prénom : </label><input type="text" style="background-color:darkgray;" readonly name="prenom" id="prenom" placeholder="votre prenom" value="'.$prenom.'" required /></br>
					<label>Date de naissance :</label><input type="date" style="background-color:darkgray;" readonly name="annaiss" id="annaiss" value="'.$dateNaiss.'" required /></br>
					<label>Sexe :</label>
					<input type="radio" name="sexe" id="Masculin" value="Masculin"' .$radioMchecked.' required /> Homme
					<input type="radio" name="sexe" id="Feminin" value="Feminin"' .$radioFchecked.' required /> Femme</br>
					<label>Adresse :</label><input type="text" name="rue" id="rue" placeholder="Nom de rue" value="'.$rue.'" required />
					<input type="text" name="cp" id="cp" placeholder="Code postal" value="'.$cp.'" required />
					<input type="text" name="ville" id="ville" placeholder="Ville" value="'.$ville.'" required /></br>
					<label>Téléphone : </label><input type="text" name="telephone" id="telephone" placeholder="06.01.02.03.04" value="'.$telephone.'" required /><br><br>
					<input id="submit1" type="submit" onclick="" name="send" class="button" value="' . $lblBouton . '" />
					</form>
					<script>function hd(){ $(\'#modal\').hide();}</script>
					<script>function reload(){window.location.reload();}</script>
					<div id="modal">
							<form id="formModale">
							<h1>Informations !</h1>
							<div id="dialog"></div>
							<input type="text" name="id" value="" style="display:none;"/>
							<input type="submit" value="Ok"/>
							</form>
					</div>
					</article>
					<script>
						$(\'#modal\').hide();
						$("#formulaireModifPersonneC :input").tooltipster({
													trigger:"custom",
													onlyOne: false,
													position:"bottom",
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
	
						$(\'#formulaireModifPersonneC\').submit(function(e){
	
							e.preventDefault();
							$(\'#modal\').hide();
							if($(\'#submit1\').prop("value")=="Modifier"){$url="ajax/valide_modifInfos_personneC.php";}
	
							if($("#formulaireModifPersonneC").valid())
							{
								var $sexe="M";
								if($("input[type=radio][name=sexe]:checked").attr("value")=="Feminin"){$sexe = "F";}
								var $mdp = "";
	
	
								var formData = {
									"login" : $("#login").val(),
									"mdp" : $mdp,
									"nom" : $("#nom").val(),
									"prenom" : $("#prenom").val(),
									"anNaiss" : $("#annaiss").val(),
									"sexe" : $sexe,
									"rue" : $("#rue").val(),
									"cp" : $("#cp").val(),
									"ville" : $("#ville").val(),
									"telephone" : $("#telephone").val()
								};
	
	
								var filterDataRequest = $.ajax(
								{
									type: "POST",
        							url: $url,
        							dataType: "json",
									encode : true,
        							data: formData
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
	
										$("#dialog").html($msg);$("#modal").show();
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
							$("#formulaireModifPersonneC").validate({
								rules:
								{
	
									"nom": {required: true},
									"prenom": {required: true},
									"rue": {required: true},
									"telephone": {required: true},
									"cp":{required: true,regex:/^\d{5}$/},
									"ville": {required: true},
									"rue": {required: true},
									"annaiss":{required: true},
									"login": {required :true},
									"mdp": {required : true}
								},
								messages:
								{
						        	"nom":
						          	{
						            	required: "Vous devez saisir un nom valide"
						          	},
									"prenom":
						          	{
						            	required: "Vous devez saisir un prenom valide"
						          	},
									"rue":
									{
						            	required: "Vous devez saisir une adresse valide"
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
		return $form;
	
	}
	
	//Affiche les options de "Gestion des rendez-vous"
	public function optionsRDVA(){
		return '
				<div class="col-md-5">
					<div class="optInfirmiere">
						<ul>
							<li>
								<a href="ajoutRDVA.php">Ajouter un rendez-vous</a>
							</li>
							<li>
								<a href="modifRDVA.php">Modifier un rendez-vous</a>
							</li>
							<li>
								<a href="deleteRDVA.php">Supprimer un rendez-vous</a>
							</li>
							<br><br>
							<li>
								<a href="#">Affecter un soin à un rendez-vous</a>
							</li>
							<li>
								<a href="#">Modifier un soin lors d\'un rendez-vous</a>
							</li>
							<li>
								<a href="#">Supprimer un soin lors d\'un rendez-vous</a>
							</li>
					</div>
				</div>';
				
	}
	
	//Affiche la liste des id des visites
	public function formRDV(){
		$tab = $this->mypdo->selectRDV();
		$return = '<form id="selectRDV" method ="post"><label>Veuillez choisir l\'id du rendez-vous à modifier : </label><br><select name="id" id="id">';
		if($tab && $tab != null)
		{
			while ($var = $tab->fetch(PDO::FETCH_ASSOC)){
				$return = $return.'<option value = "'.$var['id'].'">'.$var['id'].'</option>';
			}
			$return = $return.'</select>
					<input id="submit" type="submit" name="send" class="button" value="Valider" />
					</form>
					';
		}
		return $return;
	}
	
	//Liste déroulante des patients pour RDV
	public function formPatientRDV(){
		$tab = $this->mypdo->modifPatientRecupDB();
		$return = '<select name="idPatient" id="idPatient">';
		if($tab && $tab != null)
		{
			while ($var = $tab->fetch(PDO::FETCH_ASSOC)){
				$return = $return.'<option value = "'.$var['id'].'">'.$var['nom']." ".$var['prenom'].'</option>';
			}
			$return = $return.'</select>
					';
		}
		return $return;
	}
	
	//Liste déroulante des infirmieres pour RDV
	public function formInfirmiereRDV(){
		$tab = $this->mypdo->modifInfirmiereRecupDB();
		$return = '<select name="idInfirmiere" id="idInfirmiere">';
		if($tab && $tab != null)
		{
			while ($var = $tab->fetch(PDO::FETCH_ASSOC)){
				$return = $return.'<option value = "'.$var['id'].'">'.$var['nom']." ".$var['prenom'].'</option>';
			}
			$return = $return.'</select>
					';
		}
		return $return;
	}
	
	//Ajout/modif/suppression d'un rendez-vous
	public function retourne_formulaire_RDV($type, $id = ''){
		$idPatient = '';
		$idInfirmiere = '';
		$dateVisite = '';
		$heureDeb = '';
		$heureFin = '';
		$titreForm = '';
		$lblBouton = '';
		
		if($type == 'ajout')
		{
			$titreForm = "Création d'un rendez-vous :";
			$lblBouton = "Ajouter";
		}
		
		if($type == 'modif')
		{
			$titreForm = "Modification d'un rendez-vous :";
			$lblBouton = "Modifier";
		}
		
		if($type == 'suppr')
		{
			$titreForm = "Suppression d'un rendez-vous :";
			$lblBouton = "Supprimer";
		}
		
		if($type == 'suppr' || $type == 'modif')
		{
			
			$result = $this->mypdo->trouveRDV($id);
			if($result != null){
				$id = $result['id'];
				$idPatient = $result['idPatient'];
				$idInfirmiere = $result['idInfirmiere'];
				$dateVisite = $result['dateV'];
				$heureDeb = $result['heureDebut'];
				$heureFin = $result['heureFin'];
				
			}
		}
		
		$form = ' <form class="formulaireRDV" id="formulaireRDV" method ="post"><article> <h3><u>'.$titreForm.'</u></h3>';
		
		if($type == 'ajout')
		{
			$form = $form.'<label>Identifiant du patient : </label>'.$this->formPatientRDV().'<br>
					<label>Identifiant de l\'infirmiere : </label>'.$this->formInfirmiereRDV().'</br>';
		}
		elseif($type == 'suppr' || $type == 'modif')
		{
			$form = $form.'<label>Identifiant du patient : </label><input type="text" name="idPatient" id="idPatient" value="'.$idPatient.'"/><br>
					<label>Identifiant de l\'infirmiere : </label><input type="text" name="idInfirmiere" id="idInfirmiere" value="'.$idInfirmiere.'"/></br>';
		}	
		$form = $form.'
					</br><h4><u>Rendez-vous</u></h4>
					
					<label>Date de la visite :</label><input type="date" name="dateVisite" id="dateVisite" value="'.$dateVisite.'" required /></br>
					<label>Heure de début :</label><input type="time" name="heureDeb" id="heureDeb"  value="'.$heureDeb.'" required /></br>
					<label>Heure de fin :</label><input type="time" name="heureFin" id="heureFin"  value="'.$heureFin.'" required /></br>
					<input id="submit1" type="submit" onclick="" name="send" class="button" value="' . $lblBouton . '" />
					</form>
					<script>function hd(){ $(\'#modal\').hide();}</script>
					<script>function reload(){window.location.reload();}</script>
					<div id="modal">
							<form id="modal">
							<h1>Informations !</h1>
							<div id="dialog"></div>
							<input type="text" name="id" value="" style="display:none;"/>
							<input type="submit" value="Ok"/>
							</form>
					</div>
					</article>
					<script>
						$(\'#modal\').hide();
						$("#formulaireRDV :input").tooltipster({
													trigger:"custom",
													onlyOne: false,
													position:"bottom",
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
						
						$(\'#formulaireRDV\').submit(function(e){
							
							e.preventDefault();
							$(\'#modal\').hide();
							var $url ="ajax/valide_ajout_RDV.php";
							if($(\'#submit1\').prop("value")=="Modifier"){$url="ajax/valide_modif_RDV.php";}
							if($(\'#submit1\').prop("value")=="Supprimer"){$url="ajax/valide_suppr_RDV.php";}
							
							if($("#formulaireRDV").valid())
							{
								var formData = {
									
									"idPatient" : $("#idPatient").val(),
									"idInfirmiere" : $("#idInfirmiere").val(),
									"dateVisite" : $("#dateVisite").val(),
									"heureDeb" : $("#heureDeb").val(),
									"heureFin" : $("#heureFin").val()
								};
							
								
								var filterDataRequest = $.ajax(
								{
									type: "POST",
        							url: $url,
        							dataType: "json",
									encode : true,
        							data: formData
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
											if(data.message){$msg;$x=data.message;$msg+=$x;}
									}
						
										$("#dialog").html($msg);$("#modal").show();
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
							$("#formulaireRDV").validate({
								rules:
								{
													
									"idVisiteur": {required: true},
									"idInfirmiere": {required: true},
									"dateVisite": {required: true},
									"heureDeb": {required: true},
									"heureFin":{required: true}
									
								},
								messages:
								{
						        	"nom":
						          	{
						            	required: "Vous devez saisir un nom valide"
						          	},
									"prenom":
						          	{
						            	required: "Vous devez saisir un prenom valide"
						          	},
									"rue":
									{
						            	required: "Vous devez saisir une adresse valide"
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
		return $form;
	
	}
	
	//Validation d'un témoignage
	public function retourne_formulaire_temoignage($id = ''){
		$idPatient = '';
		$idAdmin = '';
		$libelle = '';
		$titreForm = 'Validation d\'un témoignage';
		$lblBouton = 'Ajouter';
	
		$result = $this->mypdo->trouveTemoignage($id);
		if($result != null){
			$id = $result['id'];
			$idPatient = $result['idPatient'];
			$idAdmin = $result['idAdmin'];
			$libelle = $result['libelle'];
	
			
		$form = ' <form class="formulaireTem" id="formulaireTem" method ="post"><article> <h3><u>'.$titreForm.'</u></h3>';
	
		$form = $form.'
					</br><h4><u>Validation du témoignage</u></h4>
					<label>Identifiant du patient : </label><input type="text" name="idPatient" id="idPatient" placeholder="id patient" value="'.$idPatient.'" required /><br>
					<label>Identifiant de l\'administrateur : </label><input type="text" name="idAdmin" id="idAdmin" placeholder="id admin" style="background-color:darkgray;" value="1" required /></br>
					<label>Libelle :</label><textarea name="libelle" id="libelle" rows="10" cols="22" required />'.$libelle.'</textarea></br>
					<input id="submit1" type="submit" onclick="" name="send" class="button" value="' . $lblBouton . '" />
					</form>
					<script>function hd(){ $(\'#modal\').hide();}</script>
					<script>function reload(){window.location.reload();}</script>
					<div id="modal">
							<form id="formModale">
							<h1>Informations !</h1>
							<div id="dialog"></div>
							<input type="text" name="id" value="" style="display:none;"/>
							<input type="submit" value="Ok"/>
							</form>
					</div>
					</article>
					<script>
						$(\'#modal\').hide();
						$("#formulaireTem :input").tooltipster({
													trigger:"custom",
													onlyOne: false,
													position:"bottom",
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
	
						$(\'#formulaireTem\').submit(function(e){
				
							e.preventDefault();
							$(\'#modal\').hide();
							var $url ="ajax/valide_ajout_tem.php";
							
				
							if($("#formulaireTem").valid())
							{
								var formData = {
									
									"idPatient" : $("#idPatient").val(),
									"idAdmin" : $("#idAdmin").val(),
									"libelle" : $("#libelle").val()									
								};
				
	
								var filterDataRequest = $.ajax(
								{
									type: "POST",
        							url: $url,
        							dataType: "json",
									encode : true,
        							data: formData
								});
				
								filterDataRequest.done(function(data)
								{
				
									if ( ! data.success)
									{
											var $msg="erreur-></br><ul style=\"list-style-type :decimal;padding:0 5%;\">";
											if (data.errors.message){
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
											if(data.message){$msg;$x=data.message;$msg+=$x;}
									}
	
										$("#dialog").html($msg);$("#modal").show();
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
							$("#formulaireTem").validate({
								rules:
								{
									"idPatient" :{required: true},
									"idAdmin" : {required: true},
									"libelle" : {required: true}
								},
							
								messages:
								{
						        	"nom":
						          	{
						            	required: "Vous devez saisir un nom valide"
						          	},
									"prenom":
						          	{
						            	required: "Vous devez saisir un prenom valide"
						          	},
									"rue":
									{
						            	required: "Vous devez saisir une adresse valide"
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
		return $form;
	
	}}
	
	//Validation d'un commentaire
	public function retourne_formulaire_commentaire($id = ''){
		$idVisite = '';
		$idInfirmiere = '';
		$idPatient = '';
		$idAdmin = '';
		$libelle = '';
		$titreForm = 'Validation d\'un commentaire';
		$lblBouton = 'Ajouter';
	
		$result = $this->mypdo->trouveCommentaire($id);
		
		if($result != null){
			$id = $result['id'];
			$idVisite = $result['idVisite'];
			$idInfirmiere = $result['idInfirmiere'];
			$idPatient = $result['idPatient'];
			$idAdmin = $result['idAdmin'];
			$libelle = $result['libelle'];
	
				
			$form = ' <form class="formulaireCom" id="formulaireCom" method ="post"><article> <h3><u>'.$titreForm.'</u></h3>';
	
			$form = $form.'
					</br><h4><u>Validation du commentaire</u></h4>
					<label>Identifiant de la visite : </label><input type="text" name="idVisite" id="idVisite" placeholder="id Visite" value="'.$idVisite.'" required /><br>
					<label>Identifiant de l\'infirmiere : </label><input type="text" name="idInfirmiere" id="idInfirmiere" placeholder="id Infirmiere patient" value="'.$idInfirmiere.'" required /><br>
					<label>Identifiant du patient : </label><input type="text" name="idPatient" id="idPatient" placeholder="id patient" value="'.$idPatient.'" required /><br>
					<label>Identifiant de l\'administrateur : </label><input type="text" name="idAdmin" id="idAdmin" placeholder="id admin" style="background-color:darkgray;" value="1" required /></br>
					<label>Libelle :</label><textarea name="libelle" id="libelle" rows="10" cols="22" required />'.$libelle.'</textarea></br>
					<input id="submit1" type="submit" onclick="" name="send" class="button" value="' . $lblBouton . '" />
					</form>
					<script>function hd(){ $(\'#modal\').hide();}</script>
					<script>function reload(){window.location.reload();}</script>
					<div id="modal">
							<form id="formModale">
							<h1>Informations !</h1>
							<div id="dialog"></div>
							<input type="text" name="id" value="" style="display:none;"/>
							<input type="submit" value="Ok"/>
							</form>
					</div>
					</article>
					<script>
						$(\'#modal\').hide();
						$("#formulaireCom :input").tooltipster({
													trigger:"custom",
													onlyOne: false,
													position:"bottom",
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
	
						$(\'#formulaireCom\').submit(function(e){
	
							e.preventDefault();
							$(\'#modal\').hide();
							var $url ="ajax/valide_ajout_com.php";
				
	
							if($("#formulaireCom").valid())
							{
								var formData = {
									"idVisite" : $("#idVisite").val(),
									"idInfirmiere" : $("#idInfirmiere").val(),
									"idPatient" : $("#idPatient").val(),
									"idAdmin" : $("#idAdmin").val(),
									"libelle" : $("#libelle").val()
								};
	
	
								var filterDataRequest = $.ajax(
								{
									type: "POST",
        							url: $url,
        							dataType: "json",
									encode : true,
        							data: formData
								});
	
								filterDataRequest.done(function(data)
								{
	
									if ( ! data.success)
									{
											var $msg="erreur-></br><ul style=\"list-style-type :decimal;padding:0 5%;\">";
											if (data.errors.message){
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
											if(data.message){$msg;$x=data.message;$msg+=$x;}
									}
	
										$("#dialog").html($msg);$("#modal").show();
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
							$("#formulaireCom").validate({
								rules:
								{
									"idVisite" :{required: true},
									"idInfirmiere" :{required: true},
									"idPatient" :{required: true},
									"idAdmin" : {required: true},
									"libelle" : {required: true}
								},
				
								messages:
								{
						        	"nom":
						          	{
						            	required: "Vous devez saisir un nom valide"
						          	},
									"prenom":
						          	{
						            	required: "Vous devez saisir un prenom valide"
						          	},
									"rue":
									{
						            	required: "Vous devez saisir une adresse valide"
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
			return $form;
	
		}
	}
	}
?>