<?php
	session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id'])){
			$site = new page_base_securisee_admin('Accueil');
	}
	else 
	{
		$site = new page_base('Accueil');
	}

	$site->affiche();
?>
