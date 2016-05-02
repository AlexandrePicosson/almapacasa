<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id'])){
			$site = new page_base_securisee_personneC('Informations personnelles');
			$site->js='jquery.tooltipster.min';
			$site->js='jquery.validate.min';
			$site->js='messages_fr';
			$site->css='tooltipster';
	}
	else 
	{
		$site = new page_base('Informations personnelles');
	}
	$controleur = new controleur();
	
	$site-> all_sidebar=$controleur->retourne_formulaire_modifsInfos_personneC("modif",$_SESSION['id']);
	$site->affiche();
?>
