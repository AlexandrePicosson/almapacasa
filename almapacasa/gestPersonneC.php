<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id'])){
			$site = new page_base_securisee_admin('Gestion des personnes de confiance');
	}
	else 
	{
		$site = new page_base('Gestion des personnes de confiance');
	}
	$controleur = new controleur();
	$site-> left_sidebar=$controleur->optionAdmin();
	$site-> right_sidebar=$controleur->optionsModifsPersonneC();
	$site->affiche();
?>