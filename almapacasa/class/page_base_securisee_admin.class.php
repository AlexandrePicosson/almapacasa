<?php
class page_base_securisee_admin extends page_base {

	public function __construct($p) {
		parent::__construct($p);
	}
	public function affiche() {
		if(!isset($_SESSION['id']))
		{
			echo '<script>document.location.href="index.php"; </script>';
		
		}
		else
		{
		parent::affiche();
		echo "admin";
		}
	}

	public function affiche_menu() {

		parent::affiche_menu();
		?>

			<div class="MD">
			<ul >
			<li >
			<a href="" >Administration</a>
				<ul >
					<li><a href="">Modifier mot de passe</a></li>
					<li ><a  href="#">Famille</a>
						<ul >
							<li><a href="">Inscrire une famille</a></li>
							<li><a href="">Modifier une famille</a></li>
							<li><a href="">Supprimer une famille</a></li>
							<li><a href="">Choisir une famille</a></li>
						</ul>
					</li>
					<li ></li>
					<li ><a href="#">Enfant</a>
						<ul >
							<li><a href="">Inscrire un enfant</a></li>
							<li><a href="">Modifier une enfant</a></li>
							<li><a href="">Supprimer une inscription enfant</a></li>
						</ul>
					</li>
				</ul>
			</li>
	</ul>
	</div>
		<?php 

	}
}
