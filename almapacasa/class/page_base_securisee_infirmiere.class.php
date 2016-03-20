<?php
class page_base_securisee_infirmiere extends page_base {

	public function __construct($p) {
		parent::__construct($p);
	}
	public function affiche() {
		if(!isset($_SESSION['id']) || !isset($_SESSION['type']))
		{
			echo '<script>document.location.href="index.php"; </script>';
		
		}	
		else
		{
			if($_SESSION['type']!='famille')
			{
				echo '<script>document.location.href="index.php"; </script>';
			}
			else 
			{
			parent::affiche();
			}
		}
	}
	public function affiche_menu() {

		parent::affiche_menu();
		?>
		<ul >
			<li ><a href="" >Administration</a>
				<ul >
					<li><a href="">Modifier mot de passe</a></li>
					<li><a href="">Modifier mes informations</a></li>
					<li><a href="">Inscrire un enfant</a></li>
					<li><a href="">Modifier une inscription</a></li>
					<li><a href="">Supprimer une inscription</a></li>
				</ul>
			</li>
		</ul>
		<?php 

	}
}
