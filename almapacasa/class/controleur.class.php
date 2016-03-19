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
	
	
	public function returnPageEquipe(){
		return '
				<div class="content_equipe">
					<div class="apercu_equipe">
						<img src="../almapacasa/images/avatar2.jpg">
						<div class="lib">
							<p>
								Alexandre Nossopic<br>
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
				';
	}
	
	public function optionAdmin(){
		return '
				<div class="col-md-5">
					<div class="optionAdmin">
						<ul>
							<li>
								<a href="#">Modifier mes informations</a>
							</li>
							<li>
								<a href="#">Gestion des patients</a>
							</li>
							<li>
								<a href="#">Gestion des infirmières</a>
							</li>
							<li>
								<a href="#">Gestion des personnes de confiance</a>
							</li>
							<li>
								<a href="#">Ajouter du contenu au site</a>
							</li>
							<li>
								<a href="#">Gestion des commentaires</a>
							</li>
					</div>
				</div>
				';
	}
}
	?>
