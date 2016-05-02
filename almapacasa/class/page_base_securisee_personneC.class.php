<?php
class page_base_securisee_personneC extends page_base {

	public function __construct($p) {
		parent::__construct($p);
	}
	public function affiche() {
		if(!isset($_SESSION['id']) && $_SESSION['type'] != 'personne_de_confiance')
		{
			echo '<script>document.location.href="index.php"; </script>';
		
		}
		else
		{
		parent::affiche();
		}
	}
	public function affiche_menu() {

		parent::affiche_menu();
		?>
		<ul class="nav navbar-nav">
				<li>
					<a href="modifsInfosPersonneC.php">Mon Compte</a>
				</li>
			</ul>
		<?php 

	}
}